<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VisitaSetores
 * @property int id_filial
 * @property string descricao
 * @property int status
 * @property string cor
 * @package App\Models
 */
class VisitaSetores extends Model
{
    public function __construct()
    {
        \App\Services\Models\ConexaoTenantService::definirConexaoTenant();
    }

    protected $table = 'visita_setores';
    public $timestamps = false;

    protected $cast = [
        'status' => 'bool'
    ];

    protected $fillable = [
        'id_filial',
        'descricao',
        'status',
        'cor'
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial');
    }
    public function cliente_visita_planner(){
            return $this->hasMany(ClienteVisitaPlanner::class,'id_setor');
    }

    public function getRelacionamentosCount()
    {
        $soma = $this->cliente_visita_planner()->count();
        return $soma;
    }
}
