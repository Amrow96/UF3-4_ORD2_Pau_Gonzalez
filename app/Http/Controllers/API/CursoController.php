<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CursoResource;
use App\Models\Curso;
use Illuminate\Http\Request;

use App\Clases\Utilitats;
use Illuminate\Database\QueryException;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cursos = Curso::with('usuaris')->get();
        return new CursoResource($cursos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $curso = new Curso();
        $curso->nombre = $request->input('nombre');
        $curso->codigo = $request->input('codigo');
        $curso->descripcion = $request->input('descripcion');
        $curso->usuario_username = $request->input('usuario_username');

        try {
            $curso->save();
            $respuesta = (new CursoResource($curso))->response()->statusCode(201);
        } catch (QueryException $e) {
            $error = Utilitats::errorMessage($e);
            $respuesta = response()->json(['error' => $error], 400);
        }
        return $respuesta;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curso = Curso::with('usuaris')->find($id);
        return new CursoResource($curso);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curso $curso)
    {

        $curso->nombre = $request->input('nombre');
        $curso->codigo = $request->input('codigo');
        $curso->descripcion = $request->input('descripcion');
        $curso->usuario_username = $request->input('usuario_username');

        try {
            $curso->save();
            $respuesta = (new CursoResource($curso))->response()->statusCode(200);
        } catch (QueryException $e) {
            $error = Utilitats::errorMessage($e);
            $respuesta = response()->json(['error' => $error], 400);
        }
        return $respuesta;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso = Curso::find($id);
        if ($curso == null) {
            $respuesta = response()->json(['error' => 'registro no encontrado'], 404);
        } else {
            try {
                $curso->delete();
                $respuesta = (new CursoResource($curso))->response()->setStatusCode(200);
            } catch (QueryException $e) {
                $mensaje = Utilitats::errorMessage($e);
                $respuesta = response()->json(['error' => $mensaje], 400);
            }
        }

        return $respuesta;
    }
}
