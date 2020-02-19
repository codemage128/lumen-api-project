<?php

namespace App\Http\Controllers;

abstract class ApiController extends Controller
{
    public function success($data)
    {
        return response()->json([
            'status' => 200,
            'data' => $data,
        ], 200);
    }

    public function unauthorized($errors = ['Unauthorized.'])
    {
        return response()->json([
            'status' => 401,
            'errors' => $errors,
        ], 401);
    }

    public function fail($errors)
    {
        return response()->json([
            'status' => 422,
            'errors' => $errors,
        ], 422);
    }
}
