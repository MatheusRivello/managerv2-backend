<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rota
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $id_retaguarda
 * @property string $descricao
 * @property float $rota_frete
 * @property string $rota_tipo_frete
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class Rota extends Model
{
    public function __construct()
    {
        \App\Services\Models\ConexaoTenantService::definirConexaoTenant();
    }

    protected $table = 'rota';
    public $timestamps = false;

    protected $casts = [
        'id_filial' => 'int',
        'rota_frete' => 'int'
    ];

    protected $fillable = [
        'id_filial',
        'id_retaguarda',
        'descricao',
        'rota_frete',
        'rota_tipo_frete'
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial');
    }

    public function getRelacionamentosCount()
    {
        $soma=0;

        return $soma;
    }
}