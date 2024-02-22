<?php

namespace App\Http\Controllers\api\v1\Config;

use App\Http\Controllers\Controller;
use App\Models\Central\GrupoRelatorio;
use App\Models\Central\Relatorio;
use App\Services\api\RelatorioService;
use Exception;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    private $service;
    private $usuario;

    public function __construct()
    {
        $this->service = new RelatorioService;
        $this->usuario = $this->service->usuarioLogado();
    }

    public function index()
    {
        return $this->service->verificarErro(Relatorio::all());
    }

    public function show($id)
    {
        return $this->service->verificarErro(Relatorio::find($id));
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_RELATORIO, $request->id);

            $relatorio = Relatorio::where('id', $request->id)->firstOrNew();

            if ($request->upload) {
                $this->service->deleteArquivo($relatorio->image);

                $upload = $this->service->salvarArquivo(
                    $request->upload,
                    TIPO_IMAGEM,
                    BD_CENTRAL,
                    [300, 300]
                );
                $relatorio->image = $upload;
            }

            if (($request->tipo_grafico != $relatorio->tipo_grafico) || !isset($request->id)) {
                $relatorio->slug = uniqid() . "/" . $request->tipo_grafico;
            }

            if (isset($request->id)) {
                $relatorio->user_alt = $this->usuario->id;
            }

            $relatorio->id_grupo = $request->grupo;
            $relatorio->titulo = $request->titulo;
            $relatorio->tipo_grafico = $request->tipo_grafico;
            $relatorio->status = $request->status;
            $relatorio->user_cad = $this->usuario->id;
            $relatorio->query = $request->campo_query;
            $relatorio->datakey = $request->datakey;
            $relatorio->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function showRelatorioPath($slug)
    {
        try {
            $relatorio = Relatorio::where('slug', "like", $slug . "%")->first();

            if (!$relatorio) {
                return $this->service->verificarErro($relatorio);
            }

            $relatorio->query = str_replace('[DB_CLIENTE]', $this->service->connection('database'), $relatorio->query);

            return response()->json($this->service->getArrayData($relatorio), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function listarRelatorios()
    {
        try {
            $listaRelatorios = $this->service->verificarErro(
                GrupoRelatorio::where('status', 1)
                    ->where('id_empresa', $this->usuario->fk_empresa)
                    ->orWhere('id_empresa', null)
                    ->with('relatorios')
                    ->get()
            );

            if (isset($listaRelatorios["message"])) {
                throw new Exception($listaRelatorios["message"], 404);
            }

            $listaRelatorios = collect($listaRelatorios)->map(function ($report) {
                if (count($report->relatorios) > 0) {
                    return $report;
                }
            })->reject(function ($report) {
                return empty($report);
            })->all();

            return response()->json($this->service->getArrayRelatorios($listaRelatorios), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 409);
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = Relatorio::find($id);

            if (!isset($registro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
