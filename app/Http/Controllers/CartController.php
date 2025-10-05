<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('service')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    public function add(Request $request, $serviceId)
    {
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'service_id' => $serviceId
            ]

        );

        return redirect()->back()->with('success', 'Jasa ditambahkan ke keranjang');
    }

    public function remove($slug)
    {
        // Hapus berdasarkan user_id & service_id slug
        Cart::where('user_id', Auth::id())
            ->where('service_id', $slug)
            ->delete();

        return redirect()->back()->with('success', 'Jasa dihapus dari keranjang');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Keranjang dikosongkan');
    }
}
