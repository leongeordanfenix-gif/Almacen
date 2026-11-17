<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProveedorRequest;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Proveedor::all()]);
    }

    public function store(StoreProveedorRequest $request)
    {
        $proveedor = Proveedor::create($request->validated());
        return response()->json(['data' => $proveedor], 201);
    }

    public function show(Proveedor $proveedore)
    {
        return response()->json(['data' => $proveedore]);
    }
}