<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendVerification;
use App\Models\Jadwal;
use App\Models\Stand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class JadwaPenjualanController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Jadwal::with('user.kategori_product', 'stand')
                ->where('is_repeat', '=', 'tidak')
                ->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addColumn('nama', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('nim', function ($row) {
                    return $row->user->nim;
                })
                ->addColumn('kategori', function ($row) {
                    return $row->user->kategori_product->name;
                })
                ->addColumn('tgl_penjualan', function ($row) {
                    return Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y');
                })
                ->addColumn('stand', function ($row) {
                    if ($row->stands_id != null) {
                        $stand = $row->stand->name;
                    } else {
                        $stand = 'Belum Memiliki Stand/Both';
                    }
                    return $stand;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status === 'pending') {
                        $status =  '<span class="badge bg-warning text-white p-2">Prosess Pengajuan</span>';
                    } elseif ($row->status === 'paid') {
                        $status =  '<span class="badge bg-primary text-white p-2">Mengirimkan Email Konfirmasi</span>';
                    } elseif ($row->status === 'tidak') {
                        $status =  '<span class="badge bg-danger text-white p-2">Tidak Bisa Berjualan</span>';
                    } elseif ($row->status === 'completed') {
                        $status =  '<span class="badge bg-success text-white p-2">Bisa Berjualan</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status === 'pending') {
                        $btn = '<a href="javscript:void(0)" class="btn btn-warning btn-sm mr-2" title="Kirim Email Konfirmasi Penjualan" data-email="' . $row->user->email . '" data-id="' . $row->id . '" id="confirm"><i class="fas fa-envelope"></i></a>';
                    } elseif ($row->status === 'completed') {
                        if ($row->status === 'completed' && $row->stands_id != null) {
                            $btn = '<a href="javscript:void(0)" class="btn btn-primary btn-sm disabled" id="detail">Selesai</a>';
                        } else {
                            $btn = '<a href="javscript:void(0)" class="btn btn-primary btn-sm"  data-kategori="' . $row->user->kategori_product->name . '" data-id="' . $row->id . '" data-name="' . $row->user->name . '" data-nim="' . $row->user->nim . '" data-kios="' . $row->user->kios . '" id="show"><i class="fas fa-store-alt"></i></a>';
                        }
                    } else {
                        $btn = '<a href="javscript:void(0)" class="btn btn-primary btn-sm" data-id="' . $row->id . '" id="repeat"><i class="fas fa-sync-alt"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.admin.jadwal.index');
    }

    public function show($id)
    {
        $data = Jadwal::with('user.kategori_product')->find($id);
        return response()->json($data);
    }

    public function sendVerification(Request $request)
    {
        $data = Jadwal::where('id', $request->jadwals_id)->first();
        $data->update(['status' => 'paid']);

        $datas = [
            'name' => $data->user->name,
            'nim' => $data->user->nim,
            'tgl_penjualan' => $data->tgl_penjualan,
        ];

        Mail::to($data->user->email)->send(new SendVerification($datas));
        return response()->json(200);
    }

    public function getJadwalNotDate(Request $request)
    {
        if (request()->ajax()) {
            $data = Jadwal::where('tgl_penjualan', '!=', $request->tgl_penjualan)
                ->where('status', 'pending')
                ->get();
            return DataTables::of($data)
                ->addColumn('nama', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('nim', function ($row) {
                    return $row->user->nim;
                })
                ->addColumn('kategori', function ($row) {
                    return $row->user->kategori_product->name;
                })
                ->addColumn('tgl_penjualan', function ($row) {
                    return Carbon::parse($row->tgl_penjualan)->translatedFormat('d F Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<input type="checkbox" name="value_id" id="value_id" value="' . $row->id . '">';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->addIndexColumn()
                ->toJson();
        }
    }

    public function switchPenjualan(Request $request)
    {
        $validtion = Validator::make($request->all(), [
            'new_jadwals_id' => 'required'
        ]);

        if ($validtion->fails()) {
            return response()->json(['errors' => $validtion->errors()]);
        }

        $newJadwal = Jadwal::with('user')->where('id', $request->new_jadwals_id)->first();
        $newJadwal->update([
            'tgl_penjualan' => $request->tgl_penjualan,
            'status' => 'paid',
        ]);

        $oldJadwal = Jadwal::where('id', $request->old_jadwals_id)->first();
        $oldJadwal->update(['is_repeat' => 'ya', 'status' => 'tidak']);

        $datas = [
            'name' => $newJadwal->user->name,
            'nim' => $newJadwal->user->nim,
            'tgl_penjualan' => $newJadwal->tgl_penjualan,
        ];

        Mail::to($newJadwal->user->email)->send(new SendVerification($datas));
        return response()->json(200);
    }

    public function getStand()
    {
        $data = Stand::all();
        return response()->json($data);
    }

    public function setStands(Request $request, $id)
    {
        $validtion = Validator::make($request->all(), [
            'stands_id' => 'required'
        ]);

        if ($validtion->fails()) {
            return response()->json(['errors' => $validtion->errors()]);
        }

        $jadwal = Jadwal::find($id);
        $jadwal->update(['stands_id' => $request->stands_id]);

        return response()->json(200);
    }
}
