<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class GrupoExternaRule
{
	public function rules()
	{
		return [
			"idFilial" => [
				"Required",
				"int"
			],
			"idRetaguarda" => [
				"Required",
				"string",
				"max:15"
			],
			"grupoDesc" => [
				"Required",
				"string",
				"max:60"
			],
			"desctoMax" => [
				"Nullable",
				"Numeric"
			],
			"status" => [
				"Required",
				"int",
				 Rule::in(0,1)
			]

		];
	}
}
