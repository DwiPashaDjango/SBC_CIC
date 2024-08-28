<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowLaporanController extends Controller
{
    public function index(Request $request)
    {
        $years = $request->year;
        $month = $request->month;

        $jadwal = Jadwal::with('user');

        if (!empty($month) && !empty($years)) {
            $jadwals = $jadwal->whereMonth('tgl_penjualan', $month)
                ->whereYear('tgl_penjualan', $years)
                ->where('status', 'completed')
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $jadwals = $jadwal->whereMonth('tgl_penjualan', Carbon::now()->month)
                ->whereYear('tgl_penjualan', Carbon::now()->year)
                ->where('status', 'completed')
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('pages.admin.laporan.index', compact('jadwals', 'month', 'years'));
    }

    public function show($id, $month, $years)
    {
        $report = Laporan::with(['users', 'product'])
            ->where('users_id', $id)
            ->whereMonth('tgl_laporan', $month)
            ->whereYear('tgl_laporan', $years)
            ->orderBy('tgl_laporan', 'DESC')
            ->get();
        return view('pages.admin.laporan.show', compact('report', 'month', 'years'));
    }

    public function generatePdf($id, $month, $years)
    {
        $report = Laporan::with(['users', 'product'])
            ->where('users_id', $id)
            ->whereMonth('tgl_laporan', $month)
            ->whereYear('tgl_laporan', $years)
            ->orderBy('tgl_laporan', 'DESC')
            ->get();

        $pdf = Pdf::loadView('pdf.report', compact('report', 'month', 'years'));
        $pdf->setBasePath(public_path());

        return $pdf->download('Laporan Penjualan Mahasiswa - ' . $report[0]->users->name . ' Bulan ' . $month . ' Tahun ' . $years . '.pdf');
    }
}
