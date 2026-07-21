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

        $query = Material::with('category')->orderBy($sort, $direction);

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->role === 'dosen') {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->user_id)
                  ->orWhereHas('mataKuliah.teachers', function($q2) use ($user) {
                      $q2->where('users.user_id', $user->user_id);
                  });
            });
        }

        $materials = $query->get();

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
        ]);

        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(['name' => $request->new_category]);
            $categoryId = $category->id;
        }

        $filesData = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('materials/files', 'public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }

        Material::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'file' => count($filesData) > 0 ? $filesData : null,
            'category_id' => $categoryId, 
            'created_by' => \Illuminate\Support\Facades\Auth::user()->user_id ?? null,
            'matakuliah_id' => $request->matakuliah_id ?? null,
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
        ]);

        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(['name' => $request->new_category]);
            $categoryId = $category->id;
        }

        $filesData = is_array($material->file) ? $material->file : [];

        // Handle file upload
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('materials/files', 'public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            $material->file = $filesData;
        }

        // Update semua field
        $material->title = $request->title;
        $material->description = $request->description;
        $material->video_url = $request->video_url;
        $material->category_id = $categoryId;
        if ($request->has('matakuliah_id')) {
            $material->matakuliah_id = $request->matakuliah_id;
        }

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