<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|min:6|regex:/(^[a-zA-Z]+[a-zA-Z0-9\\-]*$)/u|unique:users',
                'password' => 'required|min:7|regex:/(^[a-zA-Z]+[a-zA-Z0-9\\-]*$)/u',
                'konterId' => 'required'
            ], [
                'nama.required' => 'nama tidak boleh kosong',
                'username.regex' => 'username tidak boleh mengandung spasi',
                'password.required' => 'password tidak boleh kosong',
                'password.min' => 'password minimal 7 karakter',
                'username.min' => 'username minimal 6 karakter',
                'username.required' => 'username tidak boleh kosong',
                'konterId.required' => 'konter tidak boleh kosong',
                'username.unique' => 'username tersebut sudah ada',
                'password.regex' => 'password tidak boleh mengandung spasi'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password, ['rounds' => 10]),
                'role' => 'karyawan',
                'konterId' => $request->konterId,
                'status' => 'active'
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => "required",
            'password' => "required"
        ], [
            'username.required' => 'username tidak boleh kosong',
            'password' => 'password tidak boleh kosong'
        ]);

        if ($validation->fails()) return response()->json($validation->errors(), 400);
        $checkUser = User::where('username', $request->username)->first();
        if (!$checkUser) return response()->json(['message' => 'username/password salah'], 404);
        if (!Hash::check($request->password, $checkUser['password'])) return response()->json(['message' => 'username/password salah'], 404);
        if ($checkUser['status'] != 'active') return response()->json(['message' => 'akun anda sudah tidak aktiv'], 404);
        $payload = [
            'userId' => $checkUser['userId'],
            'nama' => $checkUser['nama'],
            'role' => $checkUser['role'],
            'konterId' => $checkUser['konterId']
        ];

        $key = env('JWT_SECRET');
        $access_token = JWT::encode($payload, $key);

        return response()->json(['access_token' => $access_token, 'user' => $payload], 200);
    }

    public function changePassword(Request $request)
    {
        try {
            $userId = $request->route('userId');
            $validation = Validator::make($request->all(), [
                'password' => 'required|min:7|regex:/(^[a-zA-Z]+[a-zA-Z0-9\\-]*$)/u'
            ], [
                'password.required' => 'password tidak boleh kosong',
                'password.min' => 'minimal password 7 karakter',
                'password.regex' => 'password tidak boleh mengandung spasi'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);

            $checkUser = User::find($userId);

            if (!$checkUser) return response()->json(['message' => 'data tidak ditemukan'], 404);

            $checkUser->update([
                'password' => Hash::make($request->password, ['rounds' => 10]),
            ]);

            return response()->json(['message' => "password dengan username " . $checkUser['username'] . " berhasil diperbarui"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function changeNama(Request $request)
    {
        $userId = $request->route('userId');
        $validation = Validator::make($request->all(), [
            'nama' => 'required'
        ], [
            'nama.required' => 'nama tidak boleh kosong'
        ]);

        if ($validation->fails()) return response()->json($validation->errors(), 400);
        $checkUser = User::find($userId);
        if (!$checkUser) return response()->json(['message' => 'data tidak ditemukan'], 404);

        $checkUser->update([
            'nama' => $request->nama,
        ]);

        return response()->json(['message' => "nama dengan username " . $checkUser['username'] . " berhasil diperbarui"], 200);
    }

    public function changeStatus(Request $request)
    {
        $userId = $request->route('userId');
        $validation = Validator::make($request->all(), [
            'status' => 'required'
        ], [
            'status.required' => 'status tidak boleh kosong'
        ]);

        if ($validation->fails()) return response()->json($validation->errors(), 400);
        $checkUser = User::find($userId);
        if (!$checkUser) return response()->json(['message' => 'data tidak ditemukan'], 404);

        $checkUser->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => "status dengan username " . $checkUser['username'] . " berhasil diperbarui"], 200);
    }

    public function getAllKaryawan(Request $request)
    {
        $nama = $request->query('nama');
        $status = $request->query('status');
        $konterId = $request->query('konterId');
        $limit = 10;
        $order = "DESC";
        $page = 1;
        if ($request->query('page')) $page =  intval($request->query('page'));
        if ($request->query('order')) $order = $request->query('order');
        if ($request->query('limit')) $limit = $request->query('limit');
        $offset = $limit * ($page - 1);
        $where = [
            ['role', '=', 'karyawan']
        ];
        if ($nama) $where = [...$where, ['nama', 'like', '%' . $nama . '%']];
        if ($status) $where = [...$where, ['status', '=', $status]];
        if ($konterId) $where = [...$where, ['konterId', '=', $konterId]];


        $user = User::with('konter')
            ->where($where)
            ->skip($offset)
            ->take($limit)
            ->orderBy('userId', $order)
            ->get();
        $total = User::where($where)->count();
        return response()->json(pagination($user, $page, $total, $limit), 200);
    }
}
