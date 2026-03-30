<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    public function index()
    {
        $armadas = Armada::orderBy('name', 'asc')->paginate(14);

        return view('armadas.index', compact('armadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'no_plat' => 'required|string|max:50|unique:armadas,no_plat',
        ]);

        Armada::create($request->all());

        return redirect()->route('armadas.index')->with('success', 'Armada created successfully.');
    }

    public function update(Request $request, Armada $armada)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'no_plat' => 'required|string|max:50|unique:armadas,no_plat,' . $armada->id,
        ]);

        $armada->update($request->all());

        return redirect()->route('armadas.index')->with('success', 'Armada updated successfully.');
    }

    public function destroy(Armada $armada)
    {
        $armada->delete();

        return redirect()->route('armadas.index')->with('success', 'Armada deleted successfully.');
    }
}