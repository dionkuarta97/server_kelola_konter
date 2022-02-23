<?php

namespace App\Http\Controllers;

use App\Models\ProdukPulsa;
use App\Models\ProdukVocher;
use App\Models\Pulsa;
use App\Models\Vocher;
use App\Models\Bri;
use App\Models\Chip;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function createPulsa(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => "required|unique:pulsas"
            ], [
                'nama.required' => "nama tidak boleh kosong",
                'nama.unique' => "nama tersebut sudah digunankan"
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $pulsa = Pulsa::create([
                'nama' => $request->nama,
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
            $nama = $request->nama;
            $status = $request->status;
            $pulsa = Pulsa::find($pulsaId);
            if (!$pulsa) return response()->json(['message' => 'data tidak ditemukan'], 404);
            if (!$nama) {
                $nama = $pulsa['nama'];
            }
            if (!$status) $status = $pulsa['status'];

            $pulsa->update([
                'nama' => $nama,
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
            if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];
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

    public function createBri(Request $request)
    {
        try {
            //code...
            $nama = $request->nama;
            if (!$nama) return response()->json(['message' => 'nama tidak boleh kosong'], 404);
            $bri = Bri::create([
                "nama" => $nama
            ]);

            return response()->json($bri, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createTopup(Request $request)
    {
        try {
            //code...
            $nama = $request->nama;
            if (!$nama) return response()->json(['message' => 'nama tidak boleh kosong'], 404);
            $topup = Topup::create([
                "nama" => $nama
            ]);

            return response()->json($topup, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createChip(Request $request)
    {
        try {
            //code...
            $nama = $request->nama;
            if (!$nama) return response()->json(['message' => 'nama tidak boleh kosong'], 404);
            $chip = Chip::create([
                "nama" => $nama,
                'status' => 'active'
            ]);

            return response()->json($chip, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function updateBri(Request $request)
    {
        try {
            //code...
            $id = $request->route('briId');
            $nama = $request->nama;
            $status = $request->status;
            $bri = Bri::find($id);
            if (!$bri) return response()->json(['message' => 'data tidak di temukan'], 404);
            if (!$nama) $nama = $bri['nama'];
            if (!$status) $status = $bri['status'];
            $bri->update([
                'nama' => $nama,
                'status' => $status
            ]);
            return response()->json(['message' => "data berhasil di ubah"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateChip(Request $request)
    {
        try {
            //code...
            $id = $request->route('chipId');
            $nama = $request->nama;
            $status = $request->status;
            $chip = Chip::find($id);
            if (!$chip) return response()->json(['message' => 'data tidak di temukan'], 404);
            if (!$nama) $nama = $chip['nama'];
            if (!$status) $status = $chip['status'];
            $chip->update([
                'nama' => $nama,
                'status' => $status
            ]);
            return response()->json(['message' => "data berhasil di ubah"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateTopup(Request $request)
    {
        try {
            //code...
            $id = $request->route('topupId');
            $nama = $request->nama;
            $status = $request->status;
            $topup = Topup::find($id);
            if (!$topup) return response()->json(['message' => 'data tidak di temukan'], 404);
            if (!$nama) $nama = $topup['nama'];
            if (!$status) $status = $topup['status'];
            $topup->update([
                'nama' => $nama,
                'status' => $status
            ]);
            return response()->json(['message' => "data berhasil di ubah"]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllBri(Request $request)
    {
        try {
            //code...
            $status = $request->query('status');
            $where = [];
            if ($status) $where = [['status', '=', $status]];
            $bri = Bri::where($where)->get();
            return response()->json($bri, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllChip(Request $request)
    {
        try {
            //code...
            $status = $request->query('status');
            $where = [];
            if ($status) $where = [['status', '=', $status]];
            $chip = Chip::where($where)->get();
            return response()->json($chip, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllTopup(Request $request)
    {
        try {
            //code...
            $status = $request->query('status');
            $where = [];
            if ($status) $where = [['status', '=', $status]];
            $topup = Topup::where($where)->get();
            return response()->json($topup, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteBri(Request $request)
    {
        try {
            //code...
            $id = $request->route('briId');
            $bri = Bri::find($id);
            if (!$bri) return response()->json(['message' => 'data tidak di temukan'], 404);
            $bri->delete();
            return response()->json(['message' => 'data berhasil di hapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteTopup(Request $request)
    {
        try {
            //code...
            $id = $request->route('topupId');
            $topup = Topup::find($id);
            if (!$topup) return response()->json(['message' => 'data tidak di temukan'], 404);
            $topup->delete();
            return response()->json(['message' => 'data berhasil di hapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function deleteChip(Request $request)
    {
        try {
            //code...
            $id = $request->route('chipId');
            $chip = Chip::find($id);
            if (!$chip) return response()->json(['message' => 'data tidak di temukan'], 404);
            $chip->delete();
            return response()->json(['message' => 'data berhasil di hapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
