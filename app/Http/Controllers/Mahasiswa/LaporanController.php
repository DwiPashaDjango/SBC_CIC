<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Laporan;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index()
    {
        $product = Product::where('users_id', Auth::user()->id)->select('name', 'id')->get();
        $jadwalTerbaru = Jadwal::where('users_id', Auth::user()->id)->latest()->first();

        if (request()->ajax()) {
            $start_date = request('start_date') ? Carbon::parse(request('start_date'))->startOfDay() : null;
            // dd($start_date->format('Y-m-d'));
            if (!empty($start_date)) {
                $data = Laporan::with('product')
                    ->where('users_id', Auth::user()->id)
                    ->where('tgl_laporan', '=', $start_date->format('Y-m-d'))
                    ->orderBy('tgl_laporan', 'DESC')
                    ->get();
            } else {
                $data = Laporan::with('product')
                    ->where('users_id', Auth::user()->id)
                    ->whereMonth('tgl_laporan', Carbon::now()->month)
                    ->whereYear('tgl_laporan', Carbon::now()->year)
                    ->orderBy('tgl_laporan', 'DESC')
                    ->get();
            }

            return DataTables::of($data)
                ->addColumn('tgl_laporan', function ($row) {
                    return Carbon::parse($row->tgl_laporan)->translatedFormat('l, d-F-Y');
                })
                ->addColumn('product', function ($row) {
                    return $row->product->name;
                })
                ->addColumn('harga_jual', function ($row) {
                    return number_format($row->product->harga_jual, 2);
                })
                ->addColumn('stock', function ($row) {
                    return $row->stock;
                })
                ->addColumn('terjual', function ($row) {
                    return $row->product_terjual;
                })
                ->addColumn('pendapatan', function ($row) {
                    return 'Rp.' . number_format($row->pendapatan);
                })
                ->addColumn('sisa_stock', function ($row) {
                    return $row->sisa_stock;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-2 show-image" data-public="' . asset('laporan/') . '" data-image="' . $row->image . '"><i class="fas fa-image"></i></a>';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm destroy" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.mahasiswa.laporan.index', compact('product', 'jadwalTerbaru'));
    }

    public function getProducts(Request $request)
    {
        $products_id = $request->products_id;

        $product = Product::where('id', $products_id)->first();
        $product['harga_jual'] = number_format($product['harga_jual'], 2);

        if (!$product) {
            return response()->json(['status' => 404, 'data' => null]);
        }

        return response()->json(['status' => 200, 'data' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_laporan' => 'required',
            'products_id' => 'required',
            'product_terjual' => 'required',
            'sisa_stock' => 'required',
            'pendapatan' => 'required',
            'image' => 'required'
        ], [
            'products_id.required' => 'Pilih Product Yang Ingin Di Jadikan Laporan.',
            'product_terjual.required' => 'Jumlah Product Yang Terjual Harus Di isi.',
            'sisa_stock.required' => 'Sisa Stock Produk Yang Ada Harus Di isi.',
            'pendapatan.required' => 'Pendapatan penjualan/hari harus diisi.',
            'image.required' => 'Foto Kegiatan/Penjualan harus diisi.',
        ]);

        $product = Product::where('id', $request->products_id)->first();
        $stockUpdate = $product->qty - $request->product_terjual;

        $imageMove = [];
        foreach ($request->file('image') as $img) {
            $extension = $img->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $path = public_path('laporan');
            $img->move($path, $fileName);

            $imageMove[] = $fileName;
        }

        $imageImplode = implode('|', $imageMove);

        Laporan::create([
            'users_id' => Auth::user()->id,
            'products_id' => $request->products_id,
            'tgl_laporan' => $request->tgl_laporan,
            'pendapatan' => str_replace('.', '', $request->pendapatan),
            'product_terjual' => $request->product_terjual,
            'stock' => $product->qty,
            'sisa_stock' => $request->sisa_stock,
            'image' => $imageImplode
        ]);

        $product->update(['qty' => $stockUpdate]);

        return back()->with('message', 'Berhasil Membuat Laporan');
    }

    public function destroy($id)
    {
        $datas = Laporan::find($id);

        foreach (explode('|', $datas->image) as $value) {
            unlink(public_path('laporan/' . $value));
        }

        $datas->delete();
        return response()->json(['status' => 200, 'message' => 'Laporan Berhasil Di Hapus.']);
    }

    public function generatePdf()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $data = Laporan::with('product', 'users')
            ->where('users_id', Auth::user()->id)
            ->whereMonth('tgl_laporan', $month)
            ->whereYear('tgl_laporan', $year)
            ->orderBy('tgl_laporan', 'DESC')
            ->get();

        $user = User::where('id', Auth::user()->id)->select('name', 'nim', 'prodi', 'kios')->first();
        $pdf = Pdf::loadView('pdf.laporan', compact('data', 'user'));
        return $pdf->stream('Laporan Penjualan - ' . $user->name . '.pdf');
    }
}
