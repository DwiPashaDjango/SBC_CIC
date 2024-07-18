<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowLaporanController extends Controller
{
    public function index(Request $request)
    {
        $years = $request->year;
        $month = $request->month;

        if (!empty($years) && !empty($month)) {
            $datas = Laporan::with('users', 'product')
                ->whereMonth('tgl_laporan', (int)$month)
                ->whereYear('tgl_laporan', (int)$years)
                ->orderBy('tgl_laporan', 'DESC')
                ->get();
        } else {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $datas = Laporan::with('users', 'product')
                ->whereMonth('tgl_laporan', $currentMonth)
                ->whereYear('tgl_laporan', $currentYear)
                ->orderBy('tgl_laporan', 'DESC')
                ->get();
        }

        return view('pages.admin.laporan.index', compact('datas', 'month', 'years'));
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


    public function reportMonth($month, $year)
    {
        $datas = Laporan::with('users', 'product')
            ->whereMonth('tgl_laporan', $month)
            ->whereYear('tgl_laporan', $year)
            ->orderBy('tgl_laporan', 'DESC')
            ->get();

        $pdf = PDF::loadView('pdf.report', compact('datas', 'month', 'year'));
        return $pdf->stream();
    }
}
