<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Product::where('users_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addColumn('image', function ($row) {
                    $img = '<img src="' . asset('storage/products/' . $row->image) . '" class="img-thumbnail" width="50" alt="' . $row->image . '">';
                    return $img;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->addColumn('harga_jual', function ($row) {
                    return 'Rp.' . number_format($row->harga_jual, 2);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('mhs.product.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->addIndexColumn()
                ->toJson();
        }
        return view('pages.mahasiswa.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.mahasiswa.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'qty' => 'required',
            'harga_jual' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg'
        ], [
            'name.required' => 'Nama Product Tidak Boleh Kosong.',
            'qty.required' => 'Jumlah Product Tidak Boleh Kosong.',
            'harga_jual.required' => 'Harga Jual Product Tidak Boleh Kosong.',
            'image.required' => 'Foto Product Tidak Boleh Kosong.',
            'image.mimes' => 'Foto Product Hanya Boleh Berformar JPG, JPEG, PNG.',
        ]);

        $image = $request->file('image');
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/products/', $imageName);

        Product::create([
            'users_id' => Auth::user()->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'harga_jual' => str_replace('.', '', $request->harga_jual),
            'keterangan' => $request->keterangan,
            'image' => $imageName
        ]);

        return redirect()->route('mhs.product.index')->with('message', 'Berhasil Menyimpan Data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return view('pages.mahasiswa.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'qty' => 'required',
            'harga_jual' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ], [
            'name.required' => 'Nama Product Tidak Boleh Kosong.',
            'qty.required' => 'Jumlah Product Tidak Boleh Kosong.',
            'harga_jual.required' => 'Harga Jual Product Tidak Boleh Kosong.',
            'image.required' => 'Foto Product Tidak Boleh Kosong.',
            'image.mimes' => 'Foto Product Hanya Boleh Berformar JPG, JPEG, PNG.',
        ]);

        $product = Product::find($id);

        if (!empty($request->file('image'))) {
            unlink(public_path('storage/products/' . $product->image));

            $image = $request->file('image');
            $imageName = rand() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products/', $imageName);

            $product->update([
                'name' => $request->name,
                'qty' => $request->qty,
                'harga_jual' => str_replace('.', '', $request->harga_jual),
                'keterangan' => $request->keterangan,
                'image' => $imageName
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'qty' => $request->qty,
                'harga_jual' => str_replace('.', '', $request->harga_jual),
                'keterangan' => $request->keterangan,
            ]);
        }
        return redirect()->route('mhs.product.index')->with('message', 'Berhasil Menyimpan Data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        unlink(public_path('storage/products/' . $product->image));
        $product->delete();

        return response()->json(200);
    }
}
