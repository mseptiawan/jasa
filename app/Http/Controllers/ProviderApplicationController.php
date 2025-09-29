<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderApplication;
use App\Notifications\SellerApprovedNotification;
use App\Notifications\SellerRejectedNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProviderApplicationController extends Controller
{
    // ===========================
    // Untuk User
    // ===========================

    // Halaman form apply jadi penyedia jasa
    public function index()
    {
        $application = ProviderApplication::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($application) {
            // kalau ada pengajuan, langsung redirect ke show
            return redirect()->route('provider.applications.show', $application->slug);
        }

        // kalau belum ada pengajuan, tampilkan index kosong / pesan
        return view('provider.applications.index', ['applications' => []]);
    }

    public function create()
    {
        // cek pengajuan terakhir user
        $application = ProviderApplication::where('user_id', Auth::id())
            ->latest()
            ->first();

        // kalau ada pengajuan
        if ($application) {
            if ($application->status !== 'rejected') {
                // status masih pending/approved => gak boleh daftar lagi
                return view('provider.applications.create')
                    ->with('status', 'Anda sudah mengajukan. Tunggu konfirmasi admin.');
            }
            // kalau status = rejected => biarin lanjut ke form (daftar ulang)
        }

        // user belum pernah daftar atau terakhir statusnya rejected
        return view('provider.applications.create');
    }


    // Simpan pengajuan user

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:100',
            'skills' => 'required|string|max:40',
            'experience' => 'required|string|max:100',
            'portfolio' => 'nullable|string|max:100',
            'education' => 'required|string|max:50',
            'id_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'selfie' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'phone_number.required' => 'Nomor HP wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'skills.required' => 'Skill utama wajib diisi.',
            'experience.required' => 'Pilih pengalaman kerja.',
            'education.required' => 'Pilih pendidikan terakhir.',
            'id_card.required' => 'Upload KTP / SIM wajib.',
            'selfie.required' => 'Upload selfie dengan KTP wajib.',
            'cv.required' => 'Upload CV wajib.',
            'file.mimes' => 'Format file harus sesuai: :values.',
            'file.max' => 'Ukuran file maksimal :max KB.',
        ]);

        $user = Auth::user();

        $application = new ProviderApplication();
        $application->user_id = $user->id;
        $application->phone_number = $request->phone_number;
        $application->address = $request->address;
        $application->skills = $request->skills;
        $application->experience = $request->experience;
        $application->portfolio = $request->portfolio;
        $application->education = $request->education;

        // bikin slug dari full_name + 10 digit random
        $application->slug = Str::slug($user->full_name) . '-' . random_int(1000000000, 9999999999);

        // Upload file
        if ($request->hasFile('id_card')) {
            $application->id_card = $request->file('id_card')->store('provider/id_card', 'public');
        }
        if ($request->hasFile('selfie')) {
            $application->selfie = $request->file('selfie')->store('provider/selfie', 'public');
        }
        if ($request->hasFile('cv')) {
            $application->cv = $request->file('cv')->store('provider/cv', 'public');
        }

        $application->save();

        return redirect()->route('provider.applications')->with('success', 'Pengajuan berhasil dikirim.');
    }

    // Lihat semua pengajuan user yang login


    // Detail pengajuan user
    public function show($slug)
    {
        $application = ProviderApplication::where('user_id', Auth::id())
            ->where('slug', $slug)
            ->where('status', 'pending')   // hanya ambil yang pending
            ->firstOrFail();

        return view('provider.applications.show', compact('application'));
    }


    // ===========================
    // Untuk Admin
    // ===========================

    // Semua pengajuan
    public function adminIndex()
    {
        $applications = ProviderApplication::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.provider_applications.index', compact('applications'));
    }

    // Detail pengajuan admin
    public function adminShow($slug)
    {
        $application = ProviderApplication::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('admin.provider_applications.show', compact('application'));
    }


    // Approve pengajuan
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:100',
        ]);

        $application = ProviderApplication::with('user')->findOrFail($id);

        // update status pengajuan
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        // ubah role user
        $application->user->update([
            'role' => 'seller'
        ]);

        // kirim notifikasi
        $application->user->notify(new SellerApprovedNotification());

        return redirect()->back()->with('success', 'Pengajuan disetujui, role user diubah ke seller, dan notifikasi dikirim.');
    }


    // Reject pengajuan
    // Reject pengajuan
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:100',
        ]);

        $application = ProviderApplication::with('user')->findOrFail($id);

        // update status pengajuan
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        // kirim notifikasi ke user
        $application->user->notify(
            new SellerRejectedNotification($request->admin_notes)
        );

        return redirect()->back()->with('success', 'Pengajuan ditolak dan notifikasi dikirim ke user.');
    }
}
