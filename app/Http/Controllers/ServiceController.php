<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Tampilkan semua service milik user
    public function index()
    {
        $services = Service::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('services.index', compact('services'));
    }

    // Form tambah service baru
    public function create()
    {
        $subcategories = Subcategory::all();
        return view('services.create', compact('subcategories'));
    }

    // Simpan service baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'subcategory_id' => 'required|exists:subcategories,id',
            'job_type' => 'required|string|max:50',
            'experience' => 'required|string|max:50',
            'industry' => 'required|string|max:50',
            'contact' => 'required|string|max:40',
            'address' => 'required|string',
            'service_type' => 'required|in:offline,online',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal :max karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal :min.',
            'discount_price.numeric' => 'Harga diskon harus berupa angka.',
            'discount_price.min' => 'Harga diskon minimal :min.',
            'discount_price.lte' => 'Harga diskon tidak boleh lebih besar dari harga asli.',
            'subcategory_id.required' => 'Kategori wajib dipilih.',
            'subcategory_id.exists' => 'Kategori tidak valid.',
            'job_type.required' => 'Jenis pekerjaan wajib diisi.',
            'job_type.max' => 'Jenis pekerjaan maksimal :max karakter.',
            'experience.required' => 'Pengalaman wajib diisi.',
            'experience.max' => 'Pengalaman maksimal :max karakter.',
            'industry.required' => 'Industri wajib diisi.',
            'industry.max' => 'Industri maksimal :max karakter.',
            'contact.required' => 'Kontak wajib diisi.',
            'contact.max' => 'Kontak maksimal :max karakter.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'service_type.required' => 'Tipe layanan wajib dipilih.',
            'service_type.in' => 'Tipe layanan tidak valid.',
            'images.required' => 'Minimal satu gambar harus diupload.',
            'images.*.required' => 'Gambar wajib diupload.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal :max KB.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
        ]);

        // process multiple images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('services', 'public');
            }
        }
        // pastikan harga diskon tidak lebih besar dari harga asli
        if ($request->discount_price !== null && $request->discount_price > $request->price) {
            return back()
                ->withInput()
                ->withErrors(['discount_price' => 'Harga diskon tidak boleh lebih besar dari harga asli.']);
        }

        $service = Service::create([
            'user_id' => Auth::id(),
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'job_type' => $request->job_type,
            'experience' => $request->experience,
            'industry' => $request->industry,
            'contact' => $request->contact,
            'address' => $request->address,
            'service_type' => $request->service_type,
            'images' => $images ? json_encode($images) : null,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'slug' => Str::slug($request->title) . '-' . random_int(1000000000, 9999999999),
        ]);

        return redirect()->route('services.index')->with('success', 'Service berhasil ditambahkan.');
    }


    // Detail service
    public function show($slug)
    {
        // Ambil service utama dengan relasi user, subcategory.category, reviews
        $service = \App\Models\Service::with(['user', 'subcategory.category', 'reviews'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Hitung rata-rata rating service utama
        $service->avg_rating = $service->reviews->avg('rating') ?? 0;

        // Ambil jasa lain dari subkategori sama atau dari seller sama (kecuali service ini)
        $services = \App\Models\Service::with(['user', 'reviews'])
            ->where('slug', '!=', $service->slug)
            ->where(function ($q) use ($service) {
                $q->where('subcategory_id', $service->subcategory_id)
                    ->orWhere('user_id', $service->user_id);
            })
            ->get();

        // Hitung rata-rata rating tiap jasa lain
        $services->map(function ($s) {
            $s->avg_rating = $s->reviews->avg('rating') ?? 0;
            return $s;
        });

        return view('services.show', compact('service', 'services'));
    }

    // Form edit service
    public function edit($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        // Cek apakah service sedang dipesan
        $activeOrders = $service->orders()->whereIn('status', ['pending', 'in_progress'])->count();
        if ($activeOrders > 0) {
            return redirect()->route('services.index')
                ->with('error', 'Service tidak bisa diedit karena sedang ada order aktif.');
        }

        $subcategories = Subcategory::all();
        return view('services.edit', compact('service', 'subcategories'));
    }


    public function update(Request $request, $slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        // Validasi
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'job_type' => 'nullable|string|max:40',
            'experience' => 'nullable|string|max:40',
            'industry' => 'nullable|string|max:40',
            'contact' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'service_type' => 'required|in:offline,online',
            'images.*' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal :max karakter.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal :min.',
            'discount_price.numeric' => 'Harga diskon harus berupa angka.',
            'discount_price.min' => 'Harga diskon minimal :min.',
            'discount_price.lte' => 'Harga diskon tidak boleh lebih besar dari harga asli.',
            'subcategory_id.exists' => 'Kategori tidak valid.',
            'job_type.string' => 'Jenis pekerjaan harus berupa teks.',
            'job_type.max' => 'Jenis pekerjaan maksimal :max karakter.',
            'experience.string' => 'Pengalaman harus berupa teks.',
            'experience.max' => 'Pengalaman maksimal :max karakter.',
            'industry.string' => 'Industri harus berupa teks.',
            'industry.max' => 'Industri maksimal :max karakter.',
            'contact.string' => 'Kontak harus berupa teks.',
            'contact.max' => 'Kontak maksimal :max karakter.',
            'address.string' => 'Alamat harus berupa teks.',
            'service_type.required' => 'Tipe layanan wajib dipilih.',
            'service_type.in' => 'Tipe layanan tidak valid.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal :max KB.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
        ]);

        // Validasi manual: diskon tidak boleh lebih besar dari harga
        if ($request->discount_price !== null && $request->discount_price > $request->price) {
            return back()
                ->withInput()
                ->withErrors(['discount_price' => 'Harga diskon tidak boleh lebih besar dari harga asli.']);
        }

        // Ambil gambar lama
        $oldImages = json_decode($service->images, true) ?? [];

        // Hapus gambar lama yang dipilih user
        if ($request->has('deleted_image_paths')) {
            foreach ($request->deleted_image_paths as $delPath) {
                if (($key = array_search($delPath, $oldImages)) !== false) {
                    unset($oldImages[$key]); // hapus dari array
                }
                if (Storage::disk('public')->exists($delPath)) {
                    Storage::disk('public')->delete($delPath); // hapus fisik
                }
            }
        }

        // Tambahkan gambar baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $oldImages[] = $image->store('services', 'public');
            }
        }

        // Update data service
        $service->title = $request->title;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->discount_price = $request->discount_price;
        $service->subcategory_id = $request->subcategory_id;
        $service->job_type = $request->job_type;
        $service->experience = $request->experience;
        $service->industry = $request->industry;
        $service->contact = $request->contact;
        $service->address = $request->address;
        $service->service_type = $request->service_type;
        $service->latitude = $request->latitude;
        $service->longitude = $request->longitude;
        $service->images = json_encode(array_values($oldImages)); // simpan semua gambar

        $service->save();

        return redirect()->route('services.index')->with('success', 'Service berhasil diupdate.');
    }


    // Hapus service
    public function destroy($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        // Cek apakah service sedang dipesan
        $activeOrders = $service->orders()->whereIn('status', ['pending', 'in_progress'])->count();
        if ($activeOrders > 0) {
            return redirect()->route('services.index')
                ->with('error', 'Service tidak bisa dihapus karena sedang ada order aktif.');
        }

        // Hapus gambar kalau mau, optional karena soft delete data masih ada
        if ($service->images) {
            foreach (json_decode($service->images) as $oldImage) {
                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }

        // Soft delete
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service berhasil dihapus (soft delete).');
    }


    public function nearby(Request $request)
    {

        // koordinat user, bisa dari browser geolocation
        $userLat = $request->input('lat');
        $userLng = $request->input('lng');

        if (!$userLat || !$userLng) {
            return back()->with('error', 'Lokasi tidak ditemukan');
        }

        $services = Service::selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$userLat, $userLng, $userLat])
            ->having('distance', '<', 5)
            ->orderBy('distance', 'asc')
            ->get();

        return view('services.nearby', compact('services'));
    }
    public function toggleFavorite(Service $service)
    {
        $user = auth()->user();

        if ($user->favoriteServices()->where('service_id', $service->id)->exists()) {
            $user->favoriteServices()->detach($service->id);
            $status = 'removed';
        } else {
            $user->favoriteServices()->attach($service->id);
            $status = 'added';
        }

        return back()->with('success', "Service favorit $status");
    }
    public function favorites()
    {
        $services = Auth::user()->favoriteServices()->with('user', 'reviews')->get();

        // hitung rata-rata rating setiap service
        foreach ($services as $service) {
            $service->avg_rating = $service->reviews()->avg('rating') ?? 0;
        }

        return view('services.favorites', compact('services'));
    }

    // Tampilkan semua layanan user untuk highlight
    public function highlight()
    {
        $userId = auth()->id();
        $services = Service::where('user_id', $userId)->get();
        return view('services.highlight', compact('services'));
    }

    public function showPayHighlight(Service $service)
    {
        if ($service->user_id !== auth()->id()) abort(403);

        $highlightFee      = 50000;
        $highlightDuration = 3;
        return view('services.highlight_pay', compact('service', 'highlightFee', 'highlightDuration'));
    }

    public function payHighlight(Request $request, Service $service)
    {
        if ($service->user_id !== auth()->id()) abort(403);

        $validMethods = ['bca', 'mandiri', 'bri', 'btn', 'danamon', 'ovo', 'dana', 'gopay', 'dummy_gateway'];

        $request->validate([
            'payment_method' => ['required', 'in:' . implode(',', $validMethods)],
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        $highlightFee      = 50000;
        $highlightDuration = 3;
        $highlightUntil    = now()->addDays($highlightDuration);

        $service->update([
            'is_highlight'     => true,
            'highlight_until'  => $highlightUntil,
            'highlight_fee'    => $highlightFee,
            'highlight_method' => $request->payment_method,
        ]);

        return redirect()->route('services.highlight')
            ->with('success', "Highlight '{$service->title}' aktif {$highlightDuration} hari. " .
                "Metode: {$request->payment_method}. Fee: Rp " . number_format($highlightFee, 0, ',', '.'));
    }

    public function guestIndex(Request $request)
    {
        $query = Service::with(['user', 'subcategory.category', 'reviews'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');

        // filter pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('subcategory.category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $services = $query->get();

        $services->map(function ($service) {
            $service->avg_rating = $service->reviews->avg('rating') ?? 0;
            return $service;
        });

        return view('welcome', compact('services', 'search'));
    }
}
