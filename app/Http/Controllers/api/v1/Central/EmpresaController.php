<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ConfiguracaoEmpresa;
use App\Models\Central\Dispositivo;
use App\Models\Central\Empresa;
use App\Models\Central\Perfil;
use App\Models\Central\TipoConfiguracaoEmpresa;
use App\Models\Central\Usuario;
use App\Services\api\EmpresaService;
use Exception;
use Illuminate\Http\Request;


class EmpresaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new EmpresaService;
    }

    public function index()
    {
        return $this->service->verificarErro(Empresa::all());
    }

    public function store(Request $request)
    {
        try {

            $this->service->verificarCamposRequest($request, RULE_EMPRESA_CENTRAL);

            $empresa = new Empresa();
            $empresa->razao_social = $request->razao_social;
            $empresa->nome_fantasia = $request->nome_fantasia;
            $empresa->codigo_autenticacao = $request->codigo_autenticacao;
            $empresa->cnpj = $request->cnpj;
            $empresa->email = $request->email;
            $empresa->qtd_licenca = $request->qtd_licenca;
            $empresa->contato = $request->contato;
            $empresa->telefone1 = $request->telefone1;
            $empresa->telefone2 = $request->telefone2;
            $empresa->observacao = $request->observacao;
            $empresa->usa_pw = $request->usa_pw;
            $empresa->pw_status = $request->pw_status;
            $empresa->pw_dominio = $request->pw_dominio;
            $empresa->bd_host = $request->bd_host;
            $empresa->bd_porta = $request->bd_porta;
            $empresa->bd_usuario = $request->bd_usuario;
            $empresa->bd_senha = $request->bd_senha;
            $empresa->bd_nome = $request->bd_nome;
            $empresa->bd_ssl = $request->bd_ssl;
            $empresa->ip = $request->ip;
            $empresa->dt_ultimo_login = $request->dt_ultimo_login;
            $empresa->status = $request->status;
            $empresa->dt_cadastro = $request->dt_cadastro;
            $empresa->dt_modificado = $request->dt_modificado;
            $empresa->atualizar_sincronizador = $request->atualizar_sincronizador;
            $empresa->versao_sincronizador = $request->versao_sincronizador;
            $empresa->dt_versao_sincronizador_atualizado = $request->dt_versao_sincronizador_atualizado;
            $empresa->pw_filial = $request->pw_filial;
            $empresa->pw_nome = $request->pw_nome;
            $empresa->pw_logo = $request->pw_logo;
            $empresa->pw_termo = $request->pw_termo;
            $empresa->atualizada = $request->atualizada;

            if ($empresa->save()) {

                collect($this->service->tipoConfigEmpresa($empresa->id))->map(function ($value) {
                    $tipoConfigEmpresa = new TipoConfiguracaoEmpresa;
                    $tipoConfigEmpresa->id = $value[0];
                    $tipoConfigEmpresa->fk_empresa = $value[1];
                    $tipoConfigEmpresa->tipo = $value[2];
                    $tipoConfigEmpresa->label = $value[3];
                    $tipoConfigEmpresa->save();
                });

                collect($this->service->configEmpresa($empresa->id))->map(function ($value) {
                    $configEmpresa = new ConfiguracaoEmpresa();
                    $configEmpresa->fk_empresa = $value[0];
                    $configEmpresa->fk_tipo_configuracao_empresa = $value[1];
                    $configEmpresa->tipo = $value[2];
                    $configEmpresa->valor = $value[3];
                    $configEmpresa->save();
                });

                $newPerfil = new Perfil();
                $newPerfil->descricao = "Administrador";
                $newPerfil->fk_empresa = $empresa->id;
                $newPerfil->fk_tipo_perfil = TIPO_PERFIL_EMPRESA_COMUM; //COMUM
                $newPerfil->fk_tipo_empresa = TIPO_EMPRESA_EMPRESA;

                if ($newPerfil->save()) {
                    $usuarioResponsavel = new Usuario();
                    $usuarioResponsavel->nome = $request->usuario["nome"];
                    $usuarioResponsavel->email = $request->usuario["email"];
                    $usuarioResponsavel->telefone = $request->usuario["telefone"];
                    $usuarioResponsavel->usuario = $request->usuario["usuario"];
                    $usuarioResponsavel->senha = $request->usuario["password"];
                    $usuarioResponsavel->password = bcrypt($request->usuario["password"]);
                    $usuarioResponsavel->responsavel = 1;
                    $usuarioResponsavel->fk_empresa = $empresa->id;
                    $usuarioResponsavel->fk_tipo_empresa = TIPO_EMPRESA_EMPRESA;
                    $usuarioResponsavel->fk_perfil = $newPerfil->id;
                    $usuarioResponsavel->status = 1;
                    $usuarioResponsavel->tipo_acesso = TIPO_ACESSO_RESPONSAVEL;
                    $usuarioResponsavel->save();
                }

                $empresa->fk_usuario_responsavel = $usuarioResponsavel->id;
                $empresa->save();
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $empresa = Empresa::where('id', $id)
            ->with('usuarios')
            ->get();

        return response()->json($this->service->verificarErro($empresa), isset($empresa) ? 200 : 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $empresa = Empresa::find($id);

            if (!$empresa) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 409);
            }

            $this->service->verificarCamposRequest($request, RULE_EMPRESA_CENTRAL, $id);

            if ($empresa->update($request->all())) {

                $perfil = Perfil::firstOrNew(
                    ["fk_empresa" => $empresa->id],
                    ["descricao" => "Administrador"]
                );
                
                $perfil->fk_tipo_perfil = TIPO_PERFIL_EMPRESA_COMUM;
                $perfil->fk_tipo_empresa = TIPO_EMPRESA_EMPRESA;
                $perfil->save();

                $usuarioResponsavel = Usuario::where(
                    [
                        ["fk_empresa", $empresa->id],
                        ["usuario", $request->usuario["usuario"]]
                    ]
                )->firstOrNew();

                $this->service->verificarCamposRequest($request->usuario, RULE_USUARIO_CENTRAL, $usuarioResponsavel->id);

                $usuarioResponsavel->nome = $request->usuario["nome"];
                $usuarioResponsavel->email = $request->usuario["email"];
                $usuarioResponsavel->telefone = $request->usuario["telefone"];
                $usuarioResponsavel->usuario = $request->usuario["usuario"];
                $usuarioResponsavel->senha = $request->usuario["password"];
                $usuarioResponsavel->password = bcrypt($request->usuario["password"]);
                $usuarioResponsavel->responsavel = 1;
                $usuarioResponsavel->fk_empresa = $empresa->id;
                $usuarioResponsavel->fk_tipo_empresa = TIPO_EMPRESA_EMPRESA;
                $usuarioResponsavel->fk_perfil = $perfil->id;
                $usuarioResponsavel->id_gerente_supervisor = null;
                $usuarioResponsavel->status = 1;
                $usuarioResponsavel->tipo_acesso = TIPO_ACESSO_RESPONSAVEL;
                if ($usuarioResponsavel->save()) {
                    $empresa->fk_usuario_responsavel = $usuarioResponsavel->id;
                    $empresa->save();
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $empresa = Empresa::find($id);

            if (!isset($empresa)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($empresa->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function dadosOpenConexao(Request $request)
    {
        try {
            if (!isset($request->mac) && !isset($request->empresa)) {
                throw new Exception(ERRO_CONSULTA, 403);
            }

            if (!is_null($request->mac)) {

                $dados = Dispositivo::where('mac', $request->mac)
                    ->join('empresa', 'dispositivo.fk_empresa', '=', 'empresa.id')
                    ->get(['dispositivo.id as id_dispositivo', 'empresa.id', 'dispositivo.id_vendedor', 'empresa.bd_host', 'empresa.bd_porta', 'empresa.bd_usuario', 'empresa.bd_senha', 'empresa.bd_nome']);
            } else {

                if (!is_int($request->empresa)) {
                    throw new Exception(ERRO_NA_CONSULTA, 403);
                }

                $dados = Empresa::find($request->empresa, ['id', 'bd_host', 'bd_porta', 'bd_usuario', 'bd_senha', 'bd_nome']);
            }

            if (!isset($dados)) {
                throw new Exception(MAC_NAO_ENCONTRADO, 400);
            }

            return $dados;
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function dispositivos($id)
    {
        $dispositivos = Dispositivo::where('fk_empresa', $id)->get();

        return response()->json([$this->service->verificarErro($dispositivos)], isset($dispositivos) ? 200 : 404);
    }

    public function usuarios($id)
    {
        $usuarios = Usuario::where('fk_empresa', $id)->get();

        return response()->json([$this->service->verificarErro($usuarios)], isset($usuarios) ? 200 : 404);
    }
}
