<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StandController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Stand::orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit mr-2" data-id="' . $row->id . '"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.admin.stand.index');
    }

    public function store(Request $request)
    {
        $validtion = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'Nama Stand/Both Harus Di Isi.'
        ]);

        if ($validtion->fails()) {
            return response()->json(['errors' => $validtion->errors()]);
        }

        Stand::create([
            'name' => $request->name
        ]);

        return response()->json(['status' => 200, 'message' => 'Berhasil Menyimpan Data.']);
    }

    public function show($id)
    {
        $data = Stand::find($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validtion = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'Nama Stand/Both Harus Di Isi.'
        ]);

        if ($validtion->fails()) {
            return response()->json(['errors' => $validtion->errors()]);
        }

        $data = Stand::find($id);
        $data->update(['name' => $request->name]);

        return response()->json(['status' => 200, 'message' => 'Berhasil Mengubah Data.']);
    }

    public function destroy($id)
    {
        $data = Stand::find($id);
        $data->delete();
        return response()->json(['status' => 200, 'message' => 'Berhasil Mengganti Data']);
    }
}
