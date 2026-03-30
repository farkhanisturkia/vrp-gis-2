<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    public function index()
    {
        $coordinates = Coordinate::orderBy('id', 'asc')->paginate(14);

        return view('coordinates.index', compact('coordinates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        Coordinate::create([
            'area' => $request->area,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return redirect()->route('coordinates.index')->with('success', 'Coordinate created successfully.');
    }

    public function update(Request $request, Coordinate $coordinate)
    {
        $request->validate([
            'area' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $coordinate->update([
            'area' => $request->area,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return redirect()->route('coordinates.index')->with('success', 'Coordinate updated successfully.');
    }

    public function destroy(Coordinate $coordinate)
    {
        $coordinate->delete();

        return redirect()->route('coordinates.index')->with('success', 'Coordinate deleted successfully.');
    }
}