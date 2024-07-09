<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class ShowLaporanController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->orderBy('name', 'ASC')->get();
            return DataTables::of($data)
                ->addColumn('nim', function ($row) {
                    return $row->nim;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('kios', function ($row) {
                    return $row->kios;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.laporan.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.admin.laporan.index');
    }

    public function show($id)
    {
        $data = User::where('id', $id)->first();
        $laporan = Laporan::with('product')->where('users_id', $id)->orderBy('tgl_laporan', 'DESC')->get();
        return view('pages.admin.laporan.show', compact('data', 'laporan'));
    }

    public function generatePdf($id)
    {
        $data = Laporan::with('product', 'users')->where('users_id', $id)->orderBy('tgl_laporan', 'DESC')->get();
        $user = User::where('id', $id)->select('name', 'nim', 'prodi', 'kios')->first();
        $pdf = Pdf::loadView('pdf.laporan', compact('data', 'user'));
        return $pdf->stream('Laporan Penjualan - ' . $data[0]->users->name . '.pdf');
    }
}
