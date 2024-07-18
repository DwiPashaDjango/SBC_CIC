<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PengajuanJadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Jadwal::with('user', 'stand')->where('users_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addColumn('kios', function ($row) {
                    return $row->user->kios;
                })
                ->addColumn('tgl_penjualan', function ($row) {
                    return Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y');
                })
                ->addColumn('stands', function ($row) {
                    if ($row->stands_id != null) {
                        return $row->stand->name;
                    } else {
                        return 'Belum Memiliki Stand/Both Penjualan';
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->status === 'pending') {
                        $status =  '<span class="badge bg-warning text-white p-2">Prosess Pengajuan</span>';
                    } elseif ($row->status === 'paid') {
                        $status =  '<span class="badge bg-primary text-white p-2">Konfirmasi penjualan Anda Sebelum Lewat Jam 3 Sore </span>';
                    } elseif ($row->status === 'tidak') {
                        $status =  '<span class="badge bg-danger text-white p-2">Tidak Bisa Berjualan Pada Tanggal ' . Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y') . '</span>';
                    } elseif ($row->status === 'completed') {
                        $status =  '<span class="badge bg-success text-white p-2">Bisa Berjualan Pada Tanggal ' . Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status === 'pending') {
                        $btn = '<a href="javscript:void(0)" class="btn btn-warning btn-sm mr-2" data-id="' . $row->id . '" id="edit"><i class="fas fa-pen"></i></a>';
                        $btn .= '<a href="javscript:void(0)" class="btn btn-danger btn-sm" data-id="' . $row->id . '" id="delete"><i class="fas fa-trash"></i></a>';
                    } elseif ($row->status === 'paid') {
                        $btn = '<a href="javscript:void(0)" class="btn btn-warning btn-sm mr-2" data-id="' . $row->id . '" id="accepted">Bisa</a>';
                        $btn .= '<a href="javscript:void(0)" class="btn btn-danger btn-sm" data-tgl="' . Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y') . '" data-id="' . $row->id . '" id="rejected">Tidak</a>';
                    } elseif ($row->status === 'tidak') {
                        $btn = '<a href="javscript:void(0)" class="btn btn-primary btn-sm" data-id="' . $row->id . '" id="show"><i class="fas fa-eye"></i></a>';
                    } elseif ($row->status === 'completed') {
                        $btn = '<a href="javscript:void(0)" class="btn btn-primary btn-sm" data-id="' . $row->id . '" id="show"><i class="fas fa-eye"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.mahasiswa.jadwal.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'tgl_penjualan' => 'required'
        ], [
            'tgl_penjualan.required' => 'Tanggal Penjualan Harus Di Isi.'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        $nextSaturday = Carbon::parse($request->tgl_penjualan)->locale('id')->addDays(6);

        Jadwal::create([
            'users_id' => Auth::user()->id,
            'kategori_products_id' => $request->kategori_products_id,
            'tgl_penjualan' => $request->tgl_penjualan,
            'tgl_akhir' => $nextSaturday,
            'status' => 'pending',
            'is_repeat' => 'tidak'
        ]);

        return response()->json(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Jadwal::find($id);
        if ($data) {
            return response()->json(['status' => 200, 'data' => $data]);
        }
        return response()->json(['status' => 404]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            'tgl_penjualan' => 'required'
        ], [
            'tgl_penjualan.required' => 'Tanggal Penjualan Harus Di Isi.'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        $data = Jadwal::find($id);
        // $data->update([
        //     'tgl_penjualan' => $request->tgl_penjualan
        // ]);

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Jadwal::find($id);
        $data->delete();
        return response()->json(200);
    }

    /**
     * Get the specified resource from storage.
     */
    public function getJadwalByTgl(Request $request)
    {
        $jadwal = Jadwal::where('tgl_penjualan', $request->tgl_penjualan)
            ->where('kategori_products_id', $request->kategori_products_id)
            ->get();

        return response()->json([
            'jadwal' => $jadwal,
        ]);
    }

    public function accepted($id)
    {
        $data = Jadwal::find($id);
        $data->update(['status' => 'completed']);
        return response()->json(200);
    }

    public function rejected($id)
    {
        $data = Jadwal::find($id);
        $data->update(['status' => 'tidak']);
        return response()->json(200);
    }
}
