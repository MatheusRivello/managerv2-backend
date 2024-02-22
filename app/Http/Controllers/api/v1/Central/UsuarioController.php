<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Mail\resetPassword;
use App\Models\Central\TokenPassword;
use App\Models\Central\Usuario;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return $this->service->verificarErro(
            Usuario::with("perfil:id,descricao")->get()
        );
    }

    public function indexSIG()
    {
        return $this->service->verificarErro(Usuario::where("fk_empresa", null)->get());
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_USUARIO_CENTRAL);

            $user = new Usuario;
            $user->fk_empresa = $this->service->usuarioLogado()->fk_empresa;
            $user->fk_perfil = $request->fk_perfil;
            $user->fk_tipo_empresa = $request->fk_tipo_empresa;
            $user->id_gerente_supervisor = $request->id_gerente_supervisor;
            $user->nome = $request->nome;
            $user->telefone = $request->telefone;
            $user->email = $request->email;
            $user->usuario = $request->usuario;
            $user->senha = $request->senha;
            $user->password = bcrypt($request->password);
            $user->codigo_autenticacao = $request->codigo_autenticacao;
            $user->tipo_acesso = $request->tipo_acesso;
            $user->status = $request->status;
            $user->responsavel = $request->responsavel;

            $user->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $user = Usuario::where('id', $id)->get();

        return response()->json([$this->service->verificarErro($user)], isset($user) ? 200 : 404);
    }

    public function update(Request $request, $id)
    {
        try {

            $user = Usuario::find($id);

            $this->service->verificarCamposRequest($request, RULE_USUARIO_CENTRAL, $id);

            $user->update($request->all());

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function mudarSenha(Request $request)
    {
        try {
            $id = $this->service->usuarioLogado()->id;
            $user = Usuario::find($id);

            $this->service->verificarCamposRequest($request, RULE_USUARIO_TENANT, $id);
            $user->senha = $request->password;
            $user->password = bcrypt($request->password);
            $user->update();

            return response()->json(["message" => PASSWORD_CHANGED], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function sendEmailPassword(Request $request)
    {
        try {
            $user = Usuario::where('email', $request->email)->first();

            if (isset($user)) {
                $tokenPassword = uniqid('SIG');
                $tokenSalvo = TokenPassword::firstOrNew(
                    ['id_usuario' => $user->id]
                );
                $tokenSalvo->token = $tokenPassword;
                $tokenSalvo->status = 0;
                $tokenSalvo->save();

                Mail::to($user->email)->send(new resetPassword($tokenSalvo->token));
            } else {
                throw new Exception(EMAIL_USER_NOT_FOUND, 404);
            }

            return response()->json(["message" => EMAIL_ENVIADO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_USUARIO_TENANT);

            $tokenSalvo = TokenPassword::where('token', $request->token)->first();


            if (!isset($tokenSalvo)) {
                throw new Exception(TOKEN_INVALID, 404);
            } else if ($tokenSalvo->status == TOKEN_UTLIZADO) {
                throw new Exception(TOKEN_USED, 403);
            }

            $tempoLimite = date($tokenSalvo->data_cad->add('+1 hour'));
            
            if (date("Y-m-d H:i") > $tempoLimite) {
                throw new Exception(TOKEN_EXPIRED, 403);
            }

            $user = Usuario::find($tokenSalvo->id_usuario);

            $user->senha = $request->password;
            $user->password = bcrypt($request->password);

            if ($user->update()) {
                $tokenSalvo->status = 1;
                $tokenSalvo->save();
            }

            return response()->json(["message" => PASSWORD_CHANGED], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $user = Usuario::find($id);

            if (!isset($user)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($user->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
