<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Material;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{
    // halaman index
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $query = Assignment::with('material')
            ->orderBy($sort, $direction);

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->role === 'dosen') {
            $query->whereHas('material', function($q) use ($user) {
                $q->where('created_by', $user->user_id)
                  ->orWhereHas('mataKuliah.teachers', function($q2) use ($user) {
                      $q2->where('users.user_id', $user->user_id);
                  });
            });
        }

        $assignments = $query->get();

        return view('admin.assignments.index', compact(
            'assignments',
            'sort',
            'direction'
        ));
    }

    // data untuk datatables AJAX
    public function data()
    {
        $query = Assignment::with('material')->select('assignments.*');
        
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->role === 'dosen') {
            $query->whereHas('material', function($q) use ($user) {
                $q->where('created_by', $user->user_id)
                  ->orWhereHas('mataKuliah.teachers', function($q2) use ($user) {
                      $q2->where('users.user_id', $user->user_id);
                  });
            });
        }

        $assignments = $query;

        return DataTables::of($assignments)
            ->addIndexColumn()

            ->addColumn('material', function ($data) {
                return $data->material->title ?? '-';
            })

            ->addColumn('notebook', function ($data) {
                return '-';
            })

            ->addColumn('deadline', function ($data) {
                return $data->deadline ?? '-';
            })

            ->addColumn('action', function ($data) {

                $edit = '<button type="button"
                            id_target="'.$data->id.'"
                            class="btn btn-warning btn-sm edit"
                            data-toggle="modal"
                            data-target="#modal-default">
                            Edit
                        </button>';

                $delete = '<button type="button"
                            id_target="'.$data->id.'"
                            class="btn btn-danger btn-sm delete"
                            data-toggle="modal"
                            data-target="#modal-default">
                            Hapus
                        </button>';

                return $edit.' '.$delete;
            })

            ->rawColumns(['notebook','action'])
            ->make(true);
    }

    // form create
    public function create()
    {
        $materials = Material::all();

        return view('admin.assignments.create', compact('materials'));
    }

    // simpan tugas
    public function store(Request $request)
    {
        $data = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $filesData = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('assignments','public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }
        
        $data['file'] = count($filesData) > 0 ? $filesData : null;

        Assignment::create($data);

        return redirect()->back()
            ->with('success','Tugas berhasil ditambahkan');
    }

    // form edit
    public function edit(Assignment $assignment)
    {
        $materials = Material::all();

        return view('admin.assignments.edit', compact('assignment','materials'));
    }

    // update tugas
    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $filesData = is_array($assignment->file) ? $assignment->file : [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('assignments','public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            $data['file'] = $filesData;
        } else {
            $data['file'] = $assignment->file;
        }

        $assignment->update($data);

        return redirect()->back()
            ->with('success','Tugas berhasil diperbarui');
    }

    // modal konfirmasi delete
    public function delete($id)
    {
        $assignment = Assignment::findOrFail($id);

        return view('admin.assignments.delete', compact('assignment'));
    }

    // hapus tugas
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->back()
            ->with('success','Tugas berhasil dihapus');
    }
}