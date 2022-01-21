<?php

namespace App\Http\Controllers;

use App\Models\Konter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KonterController extends Controller
{
    //

    public function createKonter(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama' => "required|unique:konters"
        ], [
            'nama.required' => 'nama konter tidak boleh kosong',
            'nama.unique' => 'nama konter tersebut sudah ada'
        ]);

        if ($validation->fails()) return response()->json($validation->errors(), 400);

        $konter = Konter::create([
            'nama' => $request->nama,
            'status' => 'active'
        ]);

        return response()->json($konter, 201);
    }

    public function getAllKonter(Request $request)
    {
        $nama = $request->query('nama');
        $status = $request->query('status');
        $limit = 10;
        $order = "DESC";
        $page = 1;
        if ($request->query('page')) $page =  intval($request->query('page'));
        if ($request->query('order')) $order = $request->query('order');
        if ($request->query('limit')) $limit = $request->query('limit');
        $offset = $limit * ($page - 1);
        $where = [];
        if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];
        if ($status) $where = [...$where, ['status', '=', $status]];

        $konter = Konter::with('user')
            ->where($where)
            ->skip($offset)
            ->take($limit)
            ->orderBy('konterId', $order)
            ->get();
        $total = Konter::where($where)->count();

        return response()->json(pagination($konter, $page, $total, $limit), 200);
    }

    public function updateKonter(Request $request)
    {
        $konterId = $request->route('konterId');
        $nama = $request->nama;
        $status = $request->status;
        $konter = Konter::find($konterId);
        if (!$konter) return response()->json(['message' => 'data tidak ditemukan'], 404);
        if ($nama) {
            $checkKonter = Konter::where('nama', $nama)->first();
            if ($checkKonter) return response()->json(['message' => 'nama tesebut telah digunakan'], 400);
        } else {
            $nama = $konter['nama'];
        }
        if (!$status) $status = $konter['status'];

        $konter->update([
            'nama' => $nama,
            'status' => $status
        ]);
        return response()->json(['message' => 'data berhasil di perbarui'], 200);
    }
}
