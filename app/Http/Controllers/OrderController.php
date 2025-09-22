<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Tampilkan daftar pesanan sesuai role
    public function index()
    {
        if (auth()->user()->role === 'customer') {
            $orders = Order::with(['service.reviews', 'seller'])
                ->where('customer_id', auth()->id())
                ->get();
        } else { // seller
            $orders = Order::with(['service.reviews', 'customer'])
                ->where('seller_id', auth()->id())
                ->get();
        }

        return view('orders.index', compact('orders'));
    }


    // Simpan order baru (langsung completed)
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'payment_method' => 'required|string',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $service = Service::findOrFail($request->service_id);

        $order = Order::create([
            'customer_id' => auth()->id(),
            'seller_id' => $service->user_id,
            'service_id' => $service->id,
            'price' => $service->price,
            'status' => 'pending', // status awal pesanan
            'payment_method' => $request->payment_method,
            'customer_address' => $request->customer_address,
            'customer_phone' => $request->customer_phone,
            'note' => $request->note,
        ]);
        $service->user->notify(new \App\Notifications\NewOrderNotification($order));
        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dibuat! Status: Pending. Total: Rp ' . number_format($service->price, 0, ',', '.'));
    }

    // Opsional: khusus customer lihat order-nya
    public function myOrders()
    {
        $orders = Order::with('service', 'seller')
            ->where('customer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.my_orders', compact('orders'));
    }
    // CUSTOMER CANCEL
    public function cancel(Order $order)
    {
        // $this->authorize('update', $order); // pastikan ada policy
        if (Auth::id() !== $order->customer_id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Order tidak bisa dibatalkan.');
        }

        $order->status = 'canceled';
        $order->save();

        return back()->with('success', 'Order berhasil dibatalkan.');
    }

    // SELLER ACCEPT
    public function accept(Order $order)
    {
        // $this->authorize('update', $order);
        if (Auth::id() !== $order->seller_id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Order tidak bisa diterima.');
        }

        $order->status = 'accepted';
        $order->save();

        return back()->with('success', 'Order diterima.');
    }

    // SELLER REJECT
    public function reject(Order $order)
    {
        // $this->authorize('update', $order);
        if (Auth::id() !== $order->seller_id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Order tidak bisa ditolak.');
        }

        $order->status = 'rejected';
        $order->save();

        return back()->with('success', 'Order ditolak.');
    }

    // CUSTOMER COMPLETE
    public function complete(Order $order)
    {
        // $this->authorize('update', $order);
        if (Auth::id() !== $order->customer_id) {
            abort(403);
        }

        if ($order->status !== 'accepted') {
            return back()->with('error', 'Order tidak bisa diselesaikan.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order selesai.');
    }
}
