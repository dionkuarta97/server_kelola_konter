<?php

namespace App\Http\Controllers;

use App\Models\ProdukPulsa;
use App\Models\ProdukVocher;
use App\Models\Pulsa;
use App\Models\Vocher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function createPulsa(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama_pulsa' => "required|unique:pulsas"
            ], [
                'nama_pulsa.required' => "nama tidak boleh kosong",
                'nama_pulsa.unique' => "nama tersebut sudah digunankan"
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $pulsa = Pulsa::create([
                'nama_pulsa' => $request->nama_pulsa,
                'status' => "active"
            ]);

            return response()->json($pulsa, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function updatePulsa(Request $request)
    {
        try {
            $pulsaId = $request->route('pulsaId');
            $nama_pulsa = $request->nama_pulsa;
            $status = $request->status;
            $pulsa = Pulsa::find($pulsaId);
            if (!$pulsa) return response()->json(['message' => 'data tidak ditemukan'], 404);
            if ($nama_pulsa) {
                $checkNama = Pulsa::where('nama_pulsa', $nama_pulsa)->first();
                if ($checkNama) return response()->json(['message' => "nama tersebut sudah digunakan"], 400);
            } else {
                $nama_pulsa = $pulsa['nama_pulsa'];
            }
            if (!$status) $status = $pulsa['status'];

            $pulsa->update([
                'nama_pulsa' => $nama_pulsa,
                'status' => $status
            ]);
            return response()->json(['message' => 'data berhasil di ubah'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllPulsa(Request $request)
    {
        try {
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
            if ($nama) $where = [...$where, ['nama_pulsa', 'like', '%' . $nama . '%']];
            if ($status) $where = [...$where, ['status', '=', $status]];
            $pulsa = Pulsa::where($where)
                ->skip($offset)
                ->take($limit)
                ->orderBy('pulsaId', $order)
                ->get();
            $total = Pulsa::where($where)->count();

            return response()->json(pagination($pulsa, $page, $total, $limit), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function deletePulsa(Request $request)
    {
        try {
            $pulsaId = $request->route('pulsaId');
            $pulsa = Pulsa::find($pulsaId);
            if (!$pulsa) return response()->json(['message' => 'data tidak ditemukan'], 404);
            $produkPulsa = ProdukPulsa::where('pulsaId', $pulsa['pulsaId'])->first();
            if ($produkPulsa) return response()->json(['message' => 'ada produk terkait dengan kategori ini, data tidak bisa di hapus'], 400);
            $pulsa->delete();
            return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createVocher(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => 'required|unique:vochers',

            ], [
                'nama.required' => "nama tidak boleh kosong",
                'nama.unique' => 'nama tersebut sudah digunakan'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $vocher = Vocher::create([
                'nama' => $request->nama,
                'status' => 'active'
            ]);
            return response()->json($vocher, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateVocher(Request $request)
    {
        try {
            $vocherId = $request->route('vocherId');
            $nama = $request->nama;
            $status = $request->status;
            $vocher = Vocher::find($vocherId);
            if (!$vocher) return response()->json(['message' => 'data tidak ditemukan'], 404);
            if ($nama) {
                $checkNama = Vocher::where('nama', $nama)->first();
                if ($checkNama) return response()->json(['message' => "nama tersebut sudah digunakan"], 400);
            } else {
                $nama = $vocher['nama'];
            }
            if (!$status) $status = $vocher['status'];

            $vocher->update([
                'nama' => $nama,
                'status' => $status
            ]);
            return response()->json(['message' => 'data berhasil di ubah'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function getAllVocher(Request $request)
    {
        try {
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
            $vocher = Vocher::where($where)
                ->skip($offset)
                ->take($limit)
                ->orderBy('vocherId', $order)
                ->get();
            $total = Vocher::where($where)->count();

            return response()->json(pagination($vocher, $page, $total, $limit), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteVocher(Request $request)
    {
        try {
            $vocherId = $request->route('vocherId');
            $vocher = Vocher::find($vocherId);
            if (!$vocher) return response()->json(['message' => 'data tidak ditemukan'], 404);
            $produkVocher = ProdukVocher::where('vocherId', $vocher['vocherId'])->first();
            if ($produkVocher) return response()->json(['message' => 'ada produk terkait dengan kategori ini, data tidak bisa di hapus'], 400);
            $vocher->delete();
            return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
