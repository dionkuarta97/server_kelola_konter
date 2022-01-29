<?php

namespace App\Http\Controllers;

use App\Models\ProdukPulsa;
use App\Models\ProdukVocher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    //
    public function createProdukPulsa(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama_produk' => 'required',
                'pulsaId' => 'required',
                'harga' => 'required'
            ], [
                'nama_produk.required' => 'nama tidak boleh kosong',
                'pulsaId.required' => 'tipe pulsa tidak boleh kosong',
                'harga.required' => 'harga tidak boleh kosong'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $produkPulsa = ProdukPulsa::create([
                'nama_produk' => $request->nama_produk,
                'status' => 'active',
                'pulsaId' => $request->pulsaId,
                'harga' => $request->harga
            ]);
            return response()->json($produkPulsa, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function updateProdukPulsa(Request $request)
    {
        try {
            $produkPulsaId = $request->route('produkPulsaId');
            $nama_produk = $request->nama_produk;
            $pulsaId = $request->pulsaId;
            $status = $request->status;
            $harga = $request->harga;

            $produkPulsa = ProdukPulsa::find($produkPulsaId);
            if (!$produkPulsa) return response()->json(['message' => 'data tidak ditemukan'], 404);

            if (!$nama_produk) $nama_produk = $produkPulsa['nama_produk'];
            if (!$pulsaId) $pulsaId = $produkPulsa['pulsaId'];
            if (!$status) $status = $produkPulsa['status'];
            if (!$harga) $harga = $produkPulsa['harga'];
            $produkPulsa->update([
                'nama_produk' => $nama_produk,
                'status' => $status,
                'pulsaId' => $pulsaId,
                'harga' => $harga
            ]);
            return response()->json(['message' => 'data berhasil diubah'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function getAllProdukPulsa(Request $request)
    {
        try {
            $nama = $request->query('nama');
            $status = $request->query('status');
            $pulsaId = $request->query('pulsaId');
            $statusCategory = $request->query('statusCategory');
            $limit = 10;
            $orderType = "produkPulsaId";
            $order = "DESC";
            $page = 1;
            if ($request->query('page')) $page =  intval($request->query('page'));
            if ($request->query('order')) {
                $order = $request->query('order');
                $orderType = "harga";
            }
            if ($request->query('limit')) $limit = $request->query('limit');
            $offset = $limit * ($page - 1);
            $where = [];
            if ($nama) $where = [...$where, ['nama_produk', 'like', '%' . $nama . '%']];
            if ($status) $where = [...$where, ['status', '=', $status]];
            if ($pulsaId) $where = [...$where, ['pulsaId', '=', $pulsaId]];
            if (!$statusCategory) $statusCategory = 'active';
            $produkPulsa = ProdukPulsa::with('pulsa')
                ->whereHas('pulsa', function ($q) use ($statusCategory) {
                    $q->where('status', $statusCategory);
                })
                ->where($where)
                ->skip($offset)
                ->take($limit)
                ->orderBy($orderType, $order)
                ->get();
            $total = ProdukPulsa::with('pulsa')->whereHas('pulsa', function ($q) use ($statusCategory) {
                $q->where('status', $statusCategory);
            })->where($where)->count();
            return response()->json(pagination($produkPulsa, $page, $total, $limit), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteProdukPulsa(Request $request)
    {
        try {
            $produkPulsaId = $request->route('produkPulsaId');
            $produkPulsa = ProdukPulsa::find($produkPulsaId);
            if (!$produkPulsa) return response()->json(['message' => 'data tidak ditemukan'], 404);
            $produkPulsa->delete();
            return response()->json(['message' => 'data berhasil di hapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function createProdukVocher(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => 'required',
                'vocherId' => 'required',
                'harga' => 'required',
                'modal' => 'required',
                'stock' => 'required',
            ], [
                'nama.required' => 'nama tidak boleh kosong',
                'vocherId.required' => 'vocher tipe tidak boleh kosong',
                'harga.required' => 'harga tidak boleh kosong',
                'modal.required' => 'modal tidak boleh kosong',
                'stock.required' => 'stock tidak boleh kosong'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $produkVocher = ProdukVocher::create([
                'nama' => $request->nama,
                'vocherId' => $request->vocherId,
                'harga' => $request->harga,
                'modal' => $request->modal,
                'stock' => $request->stock,
                'status' => 'active'
            ]);

            return response()->json($produkVocher, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateProdukVocher(Request $request)
    {
        try {
            $produkVocherId = $request->route('produkVocherId');
            $nama = $request->nama;
            $vocherId = $request->vocherId;
            $status = $request->status;
            $harga = $request->harga;
            $modal = $request->modal;
            $stock = $request->stock;

            $produkVocher = ProdukVocher::find($produkVocherId);
            if (!$produkVocher) return response()->json(['message' => 'data tidak ditemukan'], 404);

            if (!$nama) $nama = $produkVocher['nama'];
            if (!$vocherId) $vocherId = $produkVocher['vocherId'];
            if (!$status) $status = $produkVocher['status'];
            if (!$harga) $harga = $produkVocher['harga'];
            if (!$modal) $modal = $produkVocher['modal'];
            if (!$stock) $stock = $produkVocher['stock'];

            $produkVocher->update([
                'nama' => $nama,
                'status' => $status,
                'vocherId' => $vocherId,
                'harga' => $harga,
                'modal' => $modal,
                'stock' => $stock,

            ]);
            return response()->json(['message' => 'data berhasil diubah'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getAllProdukVocher(Request $request)
    {
        try {
            //code...
            $nama = $request->query('nama');
            $status = $request->query('status');
            $vocherId = $request->query('vocherId');
            $statusCategory = $request->query('statusCategory');
            $limit = 10;
            $orderType = "produkVocherId";
            $order = "DESC";
            $page = 1;
            if ($request->query('page')) $page =  intval($request->query('page'));
            if ($request->query('order')) {
                $order = $request->query('order');
                $orderType = "harga";
            }
            if ($request->query('limit')) $limit = $request->query('limit');
            $offset = $limit * ($page - 1);
            $where = [];
            if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];
            if ($status) $where = [...$where, ['status', '=', $status]];
            if ($vocherId) $where = [...$where, ['vocherId', '=', $vocherId]];
            if (!$statusCategory) $statusCategory = 'active';
            $produkPulsa = ProdukVocher::with('vocher')
                ->whereHas('vocher', function ($q) use ($statusCategory) {
                    $q->where('status', $statusCategory);
                })
                ->where($where)
                ->skip($offset)
                ->take($limit)
                ->orderBy($orderType, $order)
                ->get();
            $total = ProdukVocher::with('vocher')->whereHas('vocher', function ($q) use ($statusCategory) {
                $q->where('status', $statusCategory);
            })->where($where)->count();
            return response()->json(pagination($produkPulsa, $page, $total, $limit), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function deleteProdukVocher(Request $request)
    {
        try {
            $produkVocherId = $request->route('produkVocherId');
            $produkVocher = ProdukVocher::find($produkVocherId);
            if (!$produkVocher) return response()->json(['message' => 'data tidak ditemukan'], 404);
            $produkVocher->delete();
            return response()->json(['message' => 'data berhasil di hapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
