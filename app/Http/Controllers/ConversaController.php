<?php

namespace App\Http\Controllers;

use App\Models\Conversa;
use Illuminate\Http\Request;

class ConversaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string',
            'thread_id' => 'required|string',
            'run_id' => 'required|string',
        ]);

        $conversa = Conversa::create($validated);

        return response()->json(['message' => 'Conversa criada com sucesso!', 'conversa' => $conversa], 201);
    }

    public function index()
    {
        $conversas = Conversa::all();
        return response()->json($conversas);
    }

    public function showByClientId($client_id)
    {
        $conversas = Conversa::where('client_id', $client_id)->get();

        if ($conversas->isEmpty()) {
            return response()->json(['message' => 'Nenhuma conversa encontrada para o client_id fornecido.'], 404);
        }

        return response()->json($conversas, 200);
    }

}
