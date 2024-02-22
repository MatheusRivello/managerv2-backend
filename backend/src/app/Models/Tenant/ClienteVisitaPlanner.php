<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendedorProtabelapreco
 * 
 * @property int id_cliente
 * @property int id_vendedor
 * @property int prioridade
 * @property int ordem
 * @property int dias
 * @property int id_setor
 * 
 * @package App\Models
 */
class ClienteVisitaPlanner extends Model
{
    public function __construct()
    {
        \App\Services\Models\ConexaoTenantService::definirConexaoTenant();
    }

    protected $table = 'cliente_visita_planner';
    public $timestamps = false;

    protected $cast=[
        'ordem'=>'int'
    ];
    protected $fillable = [
        'id_cliente',
        'id_vendedor',
        'prioridade',
        'ordem',
        'dias',
        'id_setor'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function vendedor(){
        return $this->belongsTo(Vendedor::class,'id_vendedor');
    }
    public function setor(){
        return $this->belongsTo(VisitaSetores::class,'id_setor');
    }

    public function getRelacionamentosCount(){
        $soma=0;
        return $soma;
    }

}