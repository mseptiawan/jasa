<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Subcategory;
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'job_type' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);


        // process multiple images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('services', 'public');
            }
        }

        $service = Service::create([
            'user_id' => Auth::id(),
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'job_type' => $request->job_type,
            'experience' => $request->experience,
            'industry' => $request->industry,
            'contact' => $request->contact,
            'address' => $request->address,
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
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('services.show', compact('service'));
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


    // Update service
    public function update(Request $request, $slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'job_type' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);


        // handle images baru
        if ($request->hasFile('images')) {
            // hapus file lama
            if ($service->images) {
                foreach (json_decode($service->images) as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('services', 'public');
            }
            $service->images = json_encode($images);
        }

        // update data lain
        $service->title = $request->title;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->subcategory_id = $request->subcategory_id;
        $service->job_type = $request->job_type;
        $service->experience = $request->experience;
        $service->industry = $request->industry;
        $service->contact = $request->contact;
        $service->address = $request->address;
        $service->latitude = $request->latitude;
        $service->longitude = $request->longitude;

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
            ->having('distance', '<', 10) // radius km, misal 50km50km
            ->orderBy('distance', 'asc')
            ->get();

        return view('services.nearby', compact('services'));
    }
    public function toggleFavorite(Service $service)
    {
        $user = auth()->user();

        if ($user->favoriteServices()->where('service_id', $service->id)->exists()) {
            $user->favoriteServices()->detach($service->id); // hapus favorit
            $status = 'removed';
        } else {
            $user->favoriteServices()->attach($service->id); // tambah favorit
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
}
