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
        // ambil semua service aktif, include user, subcategory, dan reviews
        $services = Service::with(['user', 'subcategory', 'reviews'])
            ->where('status', 'active') // filter service aktif
            ->orderBy('created_at', 'desc')
            ->get();

        // hitung rata-rata rating tiap service
        $services->map(function ($service) {
            $service->avg_rating = $service->reviews->avg('rating') ?? 0;
            return $service;
        });

        // stats opsional
        $totalUsers = Auth::check() ? User::count() : null;
        $totalServices = Service::where('status', 'active')->count(); // hanya yang aktif
        $pendingOrders = Auth::check() ? Order::where('status', 'pending')->count() : null;

        return view('dashboard', compact('services', 'totalUsers', 'totalServices', 'pendingOrders'));
    }
}
