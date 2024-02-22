<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    public function execute(array $credentials)
    {
        if (!$token = auth()->setTTL(6 * 10)->attempt($credentials)) {
            throw new Exception('Não autorizado, verifique as credenciais de acesso', 401);
        } else {
            return [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires' => auth()->factory()->getTTL()
            ];
        }
    }

    public function executeMobile(array $credentials)
    {
        //alterado para pow() pois ficara "infinito"
        if (!$token = auth('mobile')->setTTL(pow(10,9))->attempt($credentials)) {
            return ['error' => 'MAC Invalido ou Senha Incorreta'];
        } else {
            return $token;
        }
    }

    public function getDataTable($table, $select = NULL, $where = NULL)
    {
        $query = DB::connection($this->getConnection())
            ->table($table);

        if (isset($select)) {
            $query->select($select);
        }

        if (isset($where)) {
            $query->where($where);
        }

        return $query->get();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token, $ttl)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl
        ]);
    }

    private function getConnection()
    {
        return "empresa" . auth('mobile')->user()->fk_empresa;
    }
}
