<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Foto;
use Laravel\Sanctum\PersonalAccessToken;

class FotoController extends Controller
{
    public function store(Request $req)
    {
        // validate inputs
        $rules = [
            'token' => 'required',
            'kasus' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required',
            'deskripsi' => 'required',
            'foto' => 'required|image|max:2048',
        ];
        $req->validate($rules);

        // check user token
        $token = PersonalAccessToken::findToken($req->token);

        if ($token) {
            error_log('INSIDE TOKEN >>>');
            $imagePath = $req->file('foto')->store('public/images');

            // maybe need to change later if config changed for access file
            $imagePaths = explode("/", $imagePath);
            $imagePath = $imagePaths[0] . "/storage" . "/" . $imagePaths[1] . "/" . $imagePaths[2];

            $user = $token->tokenable;

            $foto = Foto::create([
                'id_user'=>$user->id,
                'kasus'=>$req->kasus,
                'lokasi'=>$req->lokasi,
                'tanggal'=>$req->tanggal,
                'deskripsi'=>$req->deskripsi,
                'foto'=>$imagePath
            ]);

            $response = ['foto'=> $foto, 'user'=> $user];
            return response()->json($response, 200);
        }

        error_log('invalid TOKEN >>>');

        $response = ['message' => 'Invalid Token'];
        return response()->json($response, 400);
    }

    public function getDetailFoto(Request $req)
    {
        // validate inputs
        $rules = [
            'token' => 'required',
        ];
        $req->validate($rules);

        // check user token
        $token = PersonalAccessToken::findToken($req->token);

        if ($token) {
            $fotos = Foto::all();

            $response = ['fotos'=> $fotos];
            return response()->json($response, 200);
        }

        $response = ['message' => 'Invalid Token'];
        return response()->json($response, 400);
    }

    public function update(Request $req)
    {
        // validate inputs
        $rules = [
            'token' => 'required',
            'id'=> 'required',
            'kasus' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required',
            'deskripsi' => 'required',
        ];
        $req->validate($rules);

        // check user token
        $token = PersonalAccessToken::findToken($req->token);

        if ($token) {
            $user = $token->tokenable;

            if ($user->level != 'ADMIN') {
                $response = ['message' => 'Hanya admin yang bisa update!'];
                return response()->json($response, 401);
            }

            $foto = Foto::where('id', $req->id)->update([
                'kasus'=>$req->kasus,
                'lokasi'=>$req->lokasi,
                'tanggal'=>$req->tanggal,
                'deskripsi'=>$req->deskripsi,
            ]);

            $foto = Foto::find($req->id);

            $response = ['foto'=> $foto, 'user'=> $user];
            return response()->json($response, 200);
        }

        error_log('invalid TOKEN >>>');

        $response = ['message' => 'Invalid Token'];
        return response()->json($response, 400);
    }

    public function delete(Request $req)
    {
        // validate inputs
        $rules = [
            'token' => 'required',
            'id'=> 'required'
        ];
        $req->validate($rules);

        // check user token
        $token = PersonalAccessToken::findToken($req->token);

        if ($token) {
            $user = $token->tokenable;

            if ($user->level != 'ADMIN') {
                $response = ['message' => 'Hanya admin yang bisa update!'];
                return response()->json($response, 401);
            }

            $fotos = Foto::destroy($req->id);

            $response = ['message'=> 'Foto dengan ID ' . $req->id . ' berhasil dihapus!'];
            return response()->json($response, 200);
        }

        $response = ['message' => 'Invalid Token'];
        return response()->json($response, 400);
    }
}
