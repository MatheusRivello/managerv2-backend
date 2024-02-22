<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\ConfiguracaoFilial;
use App\Models\Tenant\ProdutoImagem;
use App\Services\Util\ZipService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class ImgMobileController extends BaseMobileController
{
    protected $className;
    protected $zip;

    public function __construct()
    {
        $this->className = "Img";
        $this->zip = new ZipService;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }
    
    public function img($idProduto = NULL)
    {

        $PASTA_UPLOAD_PADRAO = CAMINHO_PADRAO_STORAGE;
        $PASTA_UPLOAD_EMPRESA = $PASTA_UPLOAD_PADRAO . "emp-" . $this->usuarioLogado()->fk_empresa . DIRECTORY_SEPARATOR;


        $imgPorFilial = $this->getConfigFilial("IMG_POR_FILIAL");

        $imgPorFilial = isset($imgPorFilial) && $imgPorFilial === "true";
        $dtUltimaAtualizacao = $this->getUltimaDataAtualizacao();

        if (isset($dtUltimaAtualizacao)) {
            try {
                $dtUltimaAtualizacao = new DateTime($dtUltimaAtualizacao);

                $localArquivo = $PASTA_UPLOAD_EMPRESA . "img_{$dtUltimaAtualizacao->format("d.m.y.H.i")}.zip";

                $this->limpezaArquivosAntigos($PASTA_UPLOAD_EMPRESA, $localArquivo);

                if (file_exists($localArquivo) && $idProduto == NULL) {
                    $mime = mime_content_type($localArquivo); //<-- detect file type
                    header('Content-Length: ' . filesize($localArquivo)); //<-- sends filesize header
                    header("Content-Type: $mime"); //<-- send mime-type header
                    header('Content-Disposition: inline; filename="' . $localArquivo . '";'); //<-- sends filename header
                    readfile($localArquivo);
                    // force_download($localArquivo, NULL);
                } else {
                    $this->gerarZIP($imgPorFilial, $localArquivo, $idProduto);
                }
            } catch (Exception $e) {
            }
        }
    }

    private function gerarZIP(bool $imgPorFilial, string $localArquivo, ?string $idProduto)
    {
        $data = $this->getListaImagens($imgPorFilial, $idProduto);

        if ($data !== NULL) {
            $this->addImgZip($imgPorFilial, json_decode($data, true));
            if ($idProduto == null) {
                $this->zip->archive($localArquivo);
            }
            $this->zip->download();
        }
    }

    private function getListaImagens($imgPorFilial, $idProduto = NULL)
    {
        $data = ProdutoImagem::select(
            "produto.id_retaguarda",
            "produto_imagem.caminho",
            "produto_imagem.sequencia",
        )
            ->join("produto", "produto.id", "=", "produto_imagem.id_produto");

        if ($imgPorFilial) {
            $data->addSelect(DB::raw("produto.id_filial AS id_filial"));
        } else {
            $data->whereIn("produto.id_filial", function ($subquery) {
                $subquery->select("filial.id")->from("filial")
                    ->where('filial.emp_ativa', STATUS_ATIVO)
                    ->orderBy('filial.id', 'asc');
            });
        }

        if ($idProduto != null) {
            $data->where("produto.id", $idProduto);
        }

        return $data->get();
    }
    /**
     * @param bool $imgPorFilial
     * @param array $data
     */
    private function addImgZip(bool $imgPorFilial, array $data): void
    {

        if ($imgPorFilial) {
            foreach ($data as $iValue) {
                $this->zip->read_file($iValue['caminho'], $iValue["id_retaguarda"] . '/' . $iValue['sequencia'] . '.jpg.' . $iValue["id_filial"]);
            }
        } else {
            foreach ($data as $iValue) {
                $this->zip->read_file($iValue['caminho'], $iValue["id_retaguarda"] . '/' . $iValue['sequencia'] . '.jpg');
            }
        }
    }

    /**
     * @param $PASTA_UPLOAD_EMPRESA
     * @param string $localArquivo
     */
    private function limpezaArquivosAntigos($PASTA_UPLOAD_EMPRESA, string $localArquivo): void
    {
        $files = glob($PASTA_UPLOAD_EMPRESA . '/img_*.zip');

        foreach ($files as $file) {
            if ($file != $localArquivo) {
                if (is_file($file))
                    unlink($file);
            }
        }
    }

    private function getUltimaDataAtualizacao()
    {
        $data = ProdutoImagem::select(
            DB::raw("max(dt_atualizacao) as ultimaDataAtualizacao")
        )
            ->limit(1)
            ->first();

        return isset($data) ? $data->ultimaDataAtualizacao : NULL;
    }

    private function getConfigFilial($config = null)
    {
        $data = ConfiguracaoFilial::distinct();

        if (isset($config)) {
            $data->where("descricao", $config);
            $registro = $data->first();
            $return = isset($registro) ? $registro->valor : FALSE;
        } else {
            $return = $data->get();
        }

        return $return;
    }
}
