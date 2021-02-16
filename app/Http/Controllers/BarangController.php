<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Distributor;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::get();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Ditampilkan',
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama'     => 'required|unique:barangs',
                'jumlah'     => 'required',
            ],
            [
                'required' => 'Nama tidak boleh kosong',
                'unique'   => 'Nama sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {

            $data = Barang::create([
                'nama'   => $request->nama,
                'jumlah' => $request->jumlah,
            ]);


            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data'    => $data,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama'   => 'required|unique:barangs,nama,' . $id,
                'jumlah' => 'required',
            ],
            [
                'required' => 'Nama tidak boleh kosong',
                'unique'   => 'Nama sudah ada',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {

            $data = Barang::findOrFail($id);
            $data->update([
                'nama'   => $request->nama,
                'jumlah' => $request->jumlah,
            ]);


            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Diperbaharui!',
                    'data'    => $data,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Diperbaharui!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $data = Barang::findOrFail($id);

        if ($data) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Dihapus!',
            ], 400);
        }
    }

    public function masuk(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'idbarang' => 'required',
                'jumlah'   => 'required',
            ],
            [
                'required' => 'Data tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {
            $user = Supplier::findOrFail($id);

            if ($user) {
                $data = Barang::findOrFail($request->id);
                BarangMasuk::create([
                    'barang_id'   => $data->idbarang,
                    'supplier_id' => $id,
                    'jumlah'      => $request->jumlah,
                ]);

                $data->update([
                    'jumlah' => $data->jumlah + $request->jumlah,
                ]);
            }

            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function keluar(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'idbarang' => 'required',
                'jumlah'   => 'required',
            ],
            [
                'required' => 'Data tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {
            $user = Distributor::findOrFail($id);

            if ($user) {
                $data = Barang::findOrFail($request->id);
                if ($data->jumlah > $request->jumlah) {
                    BarangKeluar::create([
                        'barang_id'   => $data->idbarang,
                        'supplier_id' => $id,
                        'jumlah'      => $request->jumlah,
                    ]);


                    $data->update([
                        'jumlah' => $data->jumlah - $request->jumlah,
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Disimpan!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Gagal Disimpan!',
                    ], 400);
                }
            }
        }
    }


    public function kuantitas(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bulan' => 'required|numeric',
            ],
            [
                'required' => 'Data tidak boleh kosong',
                'numeric' => 'Data berupa angka',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {
            // $month     = Carbon::now()->format('m');
            $datamasuk  = BarangMasuk::where(DB::raw('MONTH(created_at)'), $request->bulan)->get();
            $datakeluar = BarangKeluar::where(DB::raw('MONTH(created_at)'), $request->bulan)->get();
            // $datamasuk =BarangMasuk::where('created_at'->isoFormat('MMMM') , '02')->get();

            return response()->json([
                'success'    => true,
                'message'    => 'Data Berhasil Di Tampilkan!',
                'datamasuk'  => $datamasuk->count(),
                'datakeluar' => $datakeluar->count(),
                'datassisa'  => $datamasuk->count() - $datakeluar->count(),
            ], 200);
        }
    }


    public function ratarata(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'idsupplier' => 'required|numeric',
            ],
            [
                'required' => 'Data tidak boleh kosong',
                'numeric' => 'Data berupa angka',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ], 400);
        } else {
            $data = Supplier::findOrFail($request->idsupplier);
            if($data){
                $ratarata = BarangMasuk::selectRaw('AVG(jumlah) average, barang_id')->where('supplier_id', $request->idsupplier)->groupBy('barang_id')->get();

                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Di Tampilkan!',
                    'data'    => $ratarata,
                ], 200);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Tampilkan!',
                ], 400);
            }
        }
    }
}
