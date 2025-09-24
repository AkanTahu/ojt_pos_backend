<?php

namespace App\Http\Controllers\Auth;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Exceptions\Renderer\Exception;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'toko' => ['required', 'string', 'max:255'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            Log::info('Register called', $request->all());

            $toko = Toko::create(['nama' =>  $request->toko]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'toko_id' => $toko->id,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            $token = $user->createToken('auth_token')->plainTextToken;

            // Kirim response sesuai LoginResponse
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Register validation failed', $e->errors());
            throw $e;
        }
    }
}
