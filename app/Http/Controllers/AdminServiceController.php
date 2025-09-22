<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Notifications\ServiceStatusChanged;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();
        return view('admin.services.index', compact('services'));
    }
    public function toggleStatus($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        $service->status = $service->status === 'active' ? 'suspended' : 'active';
        $service->save();

        $service->user->notify(new ServiceStatusChanged($service, $service->status));

        $msg = $service->status === 'active' ? 'Service diaktifkan kembali.' : 'Service disuspend.';
        return redirect()->route('admin.services.index')->with('success', $msg);
    }
}
