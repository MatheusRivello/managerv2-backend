<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusProduto
 * 
 * @property int $id_produto
 * @property Carbon $sincronizado_em
 * @property Carbon atualizado_em
 * @package App\Models
 */
class SincronizacaoLojaIntegrada extends Model
{
    public function __construct()
    {
        \App\Services\Models\ConexaoTenantService::definirConexaoTenant();
    }

    protected $table = 'sincronizacao_loja_integrada';
    public $timestamps = false;

    protected $fillable = [
        'id_produto',
        'sincronizado_em',
        'sintonizado_em'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }

    public function getRelacionamentosCount()
    {
        $soma = 0;
        return $soma;
    }
}
