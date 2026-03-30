<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Armada;
use App\Models\Coordinate;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = Order::with(['from', 'to', 'user', 'armada', 'mandatories'])
            ->forCurrentUser()
            ->orderBy('date', 'asc')
            ->paginate(13);

        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $armadas = Armada::select('id', 'name', 'no_plat')->orderBy('name')->get();
        $coordinates = Coordinate::select('id', 'area')->orderBy('area')->get();

        $editOrder = null;

        if ($request->modal === 'edit' && $request->order_id) {
            $editOrder = Order::with('mandatories')
                ->where('id', $request->order_id)
                ->forCurrentUser()
                ->first();
        }

        return view('orders.index', compact(
            'orders',
            'users',
            'armadas',
            'coordinates',
            'editOrder'
        ));
    }

    public function view(Order $order) {
        $order->load('mandatories');

        return view('orders.view', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'from_id' => 'nullable|exists:coordinates,id',
            'to_id' => 'nullable|exists:coordinates,id',
            'user_id' => 'nullable|exists:users,id',
            'armada_id' => 'nullable|exists:armadas,id',
            'mandatories' => 'nullable|array'
        ]);

        $order = Order::create($request->only([
            'date',
            'from_id',
            'to_id',
            'user_id',
            'armada_id'
        ]));

        if($request->mandatories){
            $order->mandatories()->sync($request->mandatories);
        }

        return redirect()->route('orders.index')
            ->with('success','Order created successfully.');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'date' => 'required|date',
            'from_id' => 'nullable|exists:coordinates,id',
            'to_id' => 'nullable|exists:coordinates,id',
            'user_id' => 'nullable|exists:users,id',
            'armada_id' => 'nullable|exists:armadas,id',
            'mandatories' => 'nullable|array'
        ]);

        $order->update($request->only([
            'date',
            'from_id',
            'to_id',
            'user_id',
            'armada_id',
            'status'
        ]));

        $order->mandatories()->sync($request->mandatories ?? []);

        return redirect()->route('orders.index')
            ->with('success','Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:set,start,stop,end'
        ]);

        // OPTIONAL: proteksi user hanya boleh ubah order miliknya
        if (!auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status order berhasil diupdate.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success','Order deleted successfully.');
    }
}