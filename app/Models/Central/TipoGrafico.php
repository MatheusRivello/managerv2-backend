<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoGrafico
 * 
 * @property int $id
 * @property string $descricao
 * @property bool $status
 * 
 * @property Collection|Relatorio[] $relatorios
 *
 * @package App\Models
 */
class TipoGrafico extends Model
{
    protected $table = 'tipo_grafico';
    public $timestamps = false;
	public $connection = 'system';

    protected $casts = [
        'status' => 'bool'
    ];

    protected $fillable = [
        'descricao',
        'status'
    ];

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class, 'tipo_grafico');
    }
}
