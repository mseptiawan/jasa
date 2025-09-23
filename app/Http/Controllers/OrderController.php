<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
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
    public function show(Order $order)
    {
        // Pastikan user yang sedang login adalah pemilik order atau seller
        if ($order->customer_id !== auth()->id() && $order->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Load relasi service supaya bisa tampil judul layanan
        $order->load('service');

        return view('orders.show', compact('order'));
    }
    public function create(Service $service)
    {
        return view('orders.create', compact('service'));
    }
    // Simpan order baru (langsung completed)
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'payment_method' => 'required|string',
            'customer_address' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'note' => 'nullable|string|max:255',
        ], [
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'customer_address.required' => 'Alamat wajib diisi.',
            'customer_address.max' => 'Alamat maksimal :max karakter.',
            'customer_phone.required' => 'Nomor HP wajib diisi.',
            'customer_phone.max' => 'Nomor HP maksimal :max karakter.',
            'note.max' => 'Catatan maksimal :max karakter.',
        ]);

        $service = Service::findOrFail($request->service_id);

        // Hitung platform fee & total price
        $platformFee = $service->price * 0.05; // 5%
        $totalPrice = $service->price + $platformFee;

        $order = Order::create([
            'customer_id' => auth()->id(),
            'seller_id' => $service->user_id,
            'service_id' => $service->id,
            'price' => $service->price,
            'platform_fee' => $platformFee,
            'total_price' => $totalPrice,
            'status' => 'pending', // status awal pesanan
            'payment_method' => $request->payment_method,
            'customer_address' => $request->customer_address,
            'customer_phone' => $request->customer_phone,
            'note' => $request->note,
        ]);

        // Kirim notifikasi ke seller
        $service->user->notify(new \App\Notifications\NewOrderNotification($order));

        // Redirect ke invoice/show langsung
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil dibuat (simulasi).');
    }


    // Opsional: khusus customer lihat order-nya
    public function myOrders()
    {
        $user = auth()->user();

        if ($user->role === 'customer') {
            // Pesanan yang dibeli user
            $orders = Order::with('service', 'seller')
                ->where('customer_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Pesanan untuk jasa yang dimiliki seller
            $orders = Order::with('service', 'customer')
                ->whereHas('service', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

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
    public function downloadInvoice(Order $order)
    {
        $pdf = Pdf::loadView('orders.invoice_fancy', compact('order'));
        return $pdf->download('Invoice_Order_' . $order->id . '.pdf');
    }
}
