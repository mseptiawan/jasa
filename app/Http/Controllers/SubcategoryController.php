<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    // Tampilkan semua subkategori
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('name')->get();
        return view('subcategories.index', compact('subcategories'));
    }

    // Form tambah subkategori
    public function create()
    {
        $categories = Category::orderBy('name')->get(); // ambil semua category
        return view('subcategories.create', compact('categories'));
    }

    // Simpan subkategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id', // wajib pilih category
            'description' => 'nullable|string',
        ]);
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);


        return redirect()->route('subcategories.index')->with('success', 'Subkategori berhasil ditambahkan.');
    }

    // Detail subkategori
    public function show($id)
    {
        $subcategory = Subcategory::with('category')->findOrFail($id);
        return view('subcategories.show', compact('subcategory'));
    }

    // Form edit subkategori
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $categories = Category::orderBy('name')->get(); // kirim list category
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    // Update subkategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update($request->only('name', 'category_id', 'description'));

        return redirect()->route('subcategories.index')->with('success', 'Subkategori berhasil diupdate.');
    }

    // Hapus subkategori
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Subkategori berhasil dihapus.');
    }
}
