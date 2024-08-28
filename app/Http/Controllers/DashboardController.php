<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Laporan;
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
            $canceled = Jadwal::where('status', 'tidak')->count();

            $mahasiswa = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->orderBy('name', 'ASC')->count();

            return view('pages.dashboard', compact('confirmation', 'completed', 'canceled', 'mahasiswa'));
        } else {
            return view('pages.dashboard');
        }
    }

    public function getJadwalPendapatan(Request $request)
    {
        $year = $request->years;
        $months = range(1, 12);

        $datas = Laporan::select(DB::raw('MONTH(tgl_laporan) as month'), DB::raw('COUNT(*) as count'), DB::raw('SUM(pendapatan) as income'))
            ->whereYear('tgl_laporan', $year)
            ->groupBy('month')
            ->get();

        $results = [];
        foreach ($months as $month) {
            $income = $datas->where('month', $month)->first();

            $results[] = [
                'income' => $income ? $income->income : 0,
                'month' => $month
            ];
        }

        return response()->json(['income' => $results]);
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
