<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsuariResource;
use App\Models\Usuari;
use Illuminate\Http\Request;

use App\Clases\Utilitats;
use Illuminate\Database\QueryException;

class UsuariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $usuarios = Usuari::all();
        return new UsuariResource($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuari = new Usuari();
        $usuari->nom = $request->input('nom');
        $usuari->password = $request->input('password');
        $usuari->username = $request->input('username');

        try {
            $usuari->save();
            $respuesta = (new UsuariResource($usuari))->response()->statusCode(201);
        } catch (QueryException $e) {
            $error = Utilitats::errorMessage($e);
            $respuesta = response()->json(['error' => $error], 400);
        }
        return $respuesta;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $usuario = Usuari::find($username);
        return new UsuariResource($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuari $usuari)
    {
        $usuari->nom = $request->input('nom');
        $usuari->password = $request->input('password');
        $usuari->username = $request->input('username');

        try {
            $usuari->save();
            $respuesta = (new UsuariResource($usuari))->response()->statusCode(200);
        } catch (QueryException $e) {
            $error = Utilitats::errorMessage($e);
            $respuesta = response()->json(['error' => $error], 400);
        }
        return $respuesta;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function destroy($username)
    {
        $usuari = Usuari::find($username);

        try {
            $usuari->destroy();
            $respuesta = (new UsuariResource($usuari))->response()->statusCode(200);
        } catch (QueryException $e) {
            $error = Utilitats::errorMessage($e);
            $respuesta = response()->json(['error' => $error], 400);
        }
        return $respuesta;
    }
}
