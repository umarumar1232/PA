<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['title', 'category_id', 'created_at'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $materials = Material::with('category')
            ->orderBy($sort, $direction)
            ->get();

        return view('admin.materials.index', compact('materials', 'sort', 'direction'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.materials.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials/files', 'public');
        }

        Material::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'file' => $filePath,
            'category_id' => $request->category_id, 
        ]);

    return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil ditambahkan');
    }

    public function edit(Material $material)
    {
        $categories = Category::all();

        return view('admin.materials.edit', compact('material', 'categories'));
    }

    public function update(Request $request, Material $material)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials/files', 'public');
            $material->file = $filePath;
        }

        // Update semua field
        $material->title = $request->title;
        $material->description = $request->description;
        $material->video_url = $request->video_url;
        $material->category_id = $request->category_id;

        $material->save();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil dihapus');
    }
}