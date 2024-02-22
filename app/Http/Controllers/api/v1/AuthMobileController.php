<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthMobileController extends Controller
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
        // $this->middleware('auth:mobile', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('mac', 'password');

        $token = $this->authService->executeMobile($credentials);

        if (!isset($token['error'])) {
            return $this->authService->respondWithToken($token, auth('mobile')->factory()->getTTL());
        } else {
            return response()->json($token, 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $dispositivo = auth('mobile')->user();

            $dispositivo->vendedor = $this->authService->getDataTable(TABELA_VENDEDOR, NULL, ["id" => $dispositivo->id_vendedor]);;

            $select = [
                "id",
                "emp_cgc",
                "emp_raz",
                "emp_fan",
                "emp_uf",
                "emp_email",
                "emp_url_img",
            ];

            $dispositivo->filial = $this->authService->getDataTable(TABELA_FILIAL, $select, ["emp_ativa" => 1]);

            if (!isset($dispositivo)) {
                throw new Exception('Usuario nao autenticado', 401);
            }

            return response()->json($dispositivo, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth('mobile')->logout();

            return response()->json(["message" => "Token invalidado com sucesso"], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
