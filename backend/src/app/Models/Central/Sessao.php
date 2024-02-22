<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sessao
 * 
 * @property string $id
 * @property string $ip_address
 * @property int $timestamp
 * @property string $data
 *
 * @package App\Models
 */
class Sessao extends Model
{
	protected $table = 'sessao';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'timestamp' => 'int'
	];

	protected $fillable = [
		'ip_address',
		'timestamp',
		'data'
	];
}
