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
        $query = Service::with(['user', 'subcategory.category', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');

        // Filter kategori
        if ($categoryId = request('category')) {
            $query->whereHas('subcategory', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Filter search
        if ($search = request('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Filter harga
        if ($priceMin = request('price_min')) {
            $query->where('price', '>=', $priceMin);
        }
        if ($priceMax = request('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        // Filter diskon
        if (request('discount')) {
            $query->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price');
        }

        // Filter status online/offline
        if ($status = request('status')) {
            if ($status === 'online') {
                $query->whereIn('service_type', ['Full-time', 'Part-time', 'Freelance']);
            } elseif ($status === 'offline') {
                $query->where('service_type', 'offline');
            }
        }

        // Filter kota
        if ($city = request('city')) {
            $allowedCities = ['Palembang', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Makassar', 'Yogyakarta', 'Depok', 'Bekasi'];
            if (in_array($city, $allowedCities)) {
                $query->where('address', 'like', '%' . $city . '%');
            }
        }

        // Filter individu: user tertentu
        if ($userId = request('user_id')) {
            $query->where('user_id', $userId);
        }

        // Ambil data
        $services = $query->get()->map(function ($service) {
            $service->avg_rating = $service->reviews->avg('rating') ?? 0;
            return $service;
        });

        // Filter rating >=4 jika checkbox dicentang
        if (request('rating_filter')) {
            $services = $services->filter(function ($service) {
                return $service->avg_rating >= 4;
            });
        }

        // Stats opsional
        $totalUsers = Auth::check() ? User::count() : null;
        $totalServices = Service::where('status', 'active')->count();
        $pendingOrders = Auth::check() ? Order::where('status', 'pending')->count() : null;

        return view('dashboard', compact('services', 'totalUsers', 'totalServices', 'pendingOrders'));
    }
}
