<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ambil query service aktif, include relasi
        $query = \App\Models\Service::with(['user', 'subcategory.category', 'reviews'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');

        // filter berdasarkan kategori kalo ada request
        if ($categoryId = request('category')) {
            $query->whereHas('subcategory', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // filter berdasarkan search
        if ($search = request('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $services = $query->get();

        // hitung rata-rata rating tiap service
        $services->map(function ($service) {
            $service->avg_rating = $service->reviews->avg('rating') ?? 0;
            return $service;
        });

        // stats opsional
        $totalUsers = Auth::check() ? \App\Models\User::count() : null;
        $totalServices = \App\Models\Service::where('status', 'active')->count();
        $pendingOrders = Auth::check() ? \App\Models\Order::where('status', 'pending')->count() : null;

        return view('dashboard', compact('services', 'totalUsers', 'totalServices', 'pendingOrders'));
    }
}
