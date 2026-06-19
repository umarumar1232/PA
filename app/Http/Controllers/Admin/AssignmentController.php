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

        $assignments = Assignment::with('material')
            ->orderBy($sort, $direction)
            ->get();

        return view('admin.assignments.index', compact(
            'assignments',
            'sort',
            'direction'
        ));
    }

    // data untuk datatables AJAX
    public function data()
    {
        $assignments = Assignment::with('material')->select('assignments.*');

        return DataTables::of($assignments)
            ->addIndexColumn()

            ->addColumn('material', function ($data) {
                return $data->material->title ?? '-';
            })

            ->addColumn('notebook', function ($data) {
                if ($data->notebook_url) {
                    return '<a href="'.$data->notebook_url.'" target="_blank" class="btn btn-info btn-sm">Notebook</a>';
                }
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
            'notebook_url' => 'nullable|url',
            'file' => 'nullable|file',
            'deadline' => 'nullable|date'
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('assignments','public');
        }

        Assignment::create($data);

        return redirect()
            ->route('admin.assignments.index')
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
            'notebook_url' => 'nullable|url',
            'file' => 'nullable|file',
            'deadline' => 'nullable|date'
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('assignments','public');
        }

        $assignment->update($data);

        return redirect()
            ->route('admin.assignments.index')
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

        return redirect()
            ->route('admin.assignments.index')
            ->with('success','Tugas berhasil dihapus');
    }
}