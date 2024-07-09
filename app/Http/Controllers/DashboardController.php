<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Stand;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $roles = Auth::user()->roles[0]->name;

        if ($roles === 'Admin') {
            $confirmation = Jadwal::where('status', 'pending')->count();
            $completed = Jadwal::where('status', 'completed')->where('stands_id', '!=', null)->count();
            $canceled = Jadwal::where('status', 'tidak')->where('is_repeat', 'tidak')->count();
            $total = Jadwal::count();

            $mahasiswa = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->orderBy('name', 'ASC')->count();

            return view('pages.dashboard', compact('confirmation', 'completed', 'canceled', 'total', 'mahasiswa'));
        } else {
            return view('pages.dashboard');
        }
    }

    public function getJadwalPengajuan(Request $request)
    {
        $year = $request->years;
        $months = range(1, 12);
        $datas = Jadwal::where('status', 'completed')
            ->where('is_repeat', 'tidak')
            ->select(DB::raw('MONTH(tgl_penjualan) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('tgl_penjualan', $year)
            ->groupBy('month')
            ->get();

        $result = [];
        foreach ($months as $month) {
            $data = $datas->where('month', $month)->first();

            $result[] = [
                'month' => $month,
                'count' => $data ? $data->count : 0,
            ];
        }

        return response()->json($result);
    }

    public function fetchJadwal()
    {
        $jadwal = Jadwal::with('user', 'stand', 'kategori')
            ->where('status', 'completed')
            ->where('is_repeat', 'tidak')
            ->where('stands_id', '!=', null)
            ->get();

        $jadwalFetch = [];

        foreach ($jadwal as $value) {
            $jadwalFetch[] = [
                'title' => $value->user->name . ' - ' . $value->stand->name,
                'name' => $value->user->name,
                'nim' => $value->user->nim,
                'kios' => $value->user->kios,
                'kategori' => $value->kategori->name,
                'stand' => $value->stand->name,
                'tgl_penjualan' => Carbon::parse($value->tgl_penjualan)->translatedFormat('l, d F Y'),
                'tgl_akhir' => Carbon::parse($value->tgl_akhir)->translatedFormat('d F Y'),

                'start' => $value->tgl_penjualan,
                'end' => $value->tgl_akhir,
                'backgroundColor' => "#6777ef",
                'borderColor' => "#fff",
                'textColor' => '#fff'
            ];
        }

        return response()->json($jadwalFetch);
    }
}
