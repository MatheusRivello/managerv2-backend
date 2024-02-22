<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Central\Empresa;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $authService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('usuario', 'password');

            $auth = $this->authService->execute($credentials);

            return response()->json($auth, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
    public function me()
    {
        try {
            $user = $this->guard()->user();
            $user->menus = $user->perfil->menus;
            if (!isset($user)) {
                throw new Exception('Usuario nao autenticado', 401);
            }

            if (isset($user->fk_empresa)) {
                $empresa = Empresa::find($user->fk_empresa);
            } else {
                $empresa = null;
            }

            return response()->json(["database" => isset($empresa) ? $empresa->bd_nome : "central_afv", "client" => isset($empresa) ? true : false,"info_user" => $user], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()],  $ex->getCode());
        }
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(["message" => "Token invalidado com sucesso"], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken($this->guard()->refresh());
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
