<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if (!$request->user()->is_admin) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('destino')) {
            $query->where('destino', 'like', "%{$request->destino}%");
        }

        if ($request->has(['data_inicio', 'data_fim'])) {
            $query->whereBetween('data_ida', [$request->data_inicio, $request->data_fim]);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'solicitante' => 'required|string|max:255',
            'destino' => 'required|string|in:São Paulo,Rio de Janeiro,Belo Horizonte,Curitiba',
            'data_ida' => 'required|date|after_or_equal:today',
            'data_volta' => 'required|date|after_or_equal:data_ida',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        $order = $user->orders()->create([
            'solicitante' => $request->solicitante,
            'destino' => $request->destino,
            'data_ida' => $request->data_ida,
            'data_volta' => $request->data_volta,
            'status' => 'solicitado',
        ]);

        return response()->json($order, 201);
    }


    public function show(Request $request, $id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', $request->user()->id)
                      ->firstOrFail();

        return response()->json($order);
    }

    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$request->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($order->status, ['aprovado', 'cancelado'])) {
            return response()->json(['error' => 'Este pedido não pode mais ser alterado'], 400);
        }

        $order->status = 'aprovado';
        $order->save();

        return response()->json($order);
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$request->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($order->status, ['aprovado', 'cancelado'])) {
            return response()->json(['error' => 'Este pedido não pode mais ser alterado'], 400);
        }

        $order->status = 'cancelado';
        $order->save();

        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$request->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($order->status, ['aprovado', 'cancelado'])) {
            return response()->json(['error' => 'Este pedido não pode mais ser alterado'], 400);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$request->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($order->status, ['aprovado', 'cancelado'])) {
            return response()->json(['error' => 'Este pedido não pode mais ser alterado'], 400);
        }

        $request->validate([
            'solicitante' => 'required|string|max:255',
            'destino' => 'required|string|in:São Paulo,Rio de Janeiro,Belo Horizonte,Curitiba',
            'data_ida' => 'required|date|after_or_equal:today',
            'data_volta' => 'required|date|after_or_equal:data_ida',
        ]);

        $order->update($request->only(['solicitante', 'destino', 'data_ida', 'data_volta']));

        return response()->json($order);
    }


}
