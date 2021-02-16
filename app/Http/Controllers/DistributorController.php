<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributorController extends Controller
{
    public function index(){
        $data = Distributor::get();
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|unique:Distributors',
        ],
            [
                'required' => 'Nama tidak boleh kosong',
                'unique'   => 'Nama sudah ada',
            ]
        );

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ],400);

        } else {

            $data = Distributor::create([
                'nama' => $request->nama,
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

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|unique:Distributors,nama,'.$id,
        ],
            [
                'required' => 'Nama tidak boleh kosong',
                'unique'   => 'Nama sudah ada',
            ]
        );

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Cuy...',
                'data'    => $validator->errors()
            ],400);

        } else {

            $data = Distributor::findOrFail($id);
            $data->update([
                'nama' => $request->nama,
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
        $data = Distributor::findOrFail($id);

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
}
