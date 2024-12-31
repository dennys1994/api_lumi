<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);;
     
        // Busca o usuário pelo campo cpf_cnpj
        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Aqui estamos criando o token para o usuário autenticado
        $token = $user->createToken('api-token')->plainTextToken;

        // Retorna o token em resposta
        return response()->json(['token' => $token], 200);
    }

    public function generateToken(Request $request)
    {
        $user = User::find($request->user_id); // Encontre o usuário pelo ID

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $token = $user->generateApiToken(); // Gera o token aleatório

        return response()->json(['message' => 'Token gerado com sucesso!', 'api_token' => $token], 200);
    }

    public function register(Request $request)
    {
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Criação do novo usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash da senha
        ]);

        // Geração do token API
        $token = $user->generateApiToken();

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'user' => $user,
            'api_token' => $token,
        ], 201);
    }
}
