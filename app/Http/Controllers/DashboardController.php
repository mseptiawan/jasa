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
        // ambil semua service, selalu include relasi user & subcategory
        $services = Service::with(['user', 'subcategory'])->orderBy('created_at', 'desc')->get();

        // stats opsional, hanya untuk yang login
        $totalUsers = Auth::check() ? User::count() : null;
        $totalServices = Service::count();
        $pendingOrders = Auth::check() ? Order::where('status', 'pending')->count() : null;

        return view('dashboard', compact('services', 'totalUsers', 'totalServices', 'pendingOrders'));
    }
}
