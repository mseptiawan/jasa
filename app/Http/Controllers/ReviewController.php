<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Service $service)
    {
        // Pastikan customer beli jasa ini
        $order = $service->orders()
            ->where('customer_id', auth()->id())
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            abort(403, 'Anda tidak dapat memberi review pada jasa ini.');
        }

        // Cek kalau user udah review
        $existing = $service->reviews()->where('customer_id', auth()->id())->first();
        if ($existing) {
            return back()->with('info', 'Anda sudah memberi review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        Review::create([
            'service_id' => $service->id,
            'customer_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review berhasil dikirim.');
    }
}
