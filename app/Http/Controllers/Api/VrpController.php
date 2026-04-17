<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VrpService;

class VrpController extends Controller
{
    public function solve(Request $request, VrpService $vrp)
    {
        $data = $request->validate([
            'start.lat' => 'required|numeric',
            'start.lng' => 'required|numeric',
            'start.label' => 'nullable|string',

            'end.lat' => 'required|numeric',
            'end.lng' => 'required|numeric',
            'end.label' => 'nullable|string',
            
            'points' => 'nullable|array',
            'points.*.lat' => 'required|numeric',
            'points.*.lng' => 'required|numeric',
            'points.*.label' => 'nullable|string',
        ]);

        $route = $vrp->solve(
            $data['start'],
            $data['points'] ?? [],
            $data['end']
        );

        return response()->json($route);
    }
}