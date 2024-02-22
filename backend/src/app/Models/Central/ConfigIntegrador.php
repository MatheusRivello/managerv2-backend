<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ConfigIntegrador extends Model
{
    protected $table = 'config_integrador';
	public $connection = 'system';
	public $timestamps = false;

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'name',
        'value',
        'fk_empresa'
    ];

    public function empresa(){
        return $this->belongsTo(Empressa::class,'fk_empresa');
    }
}