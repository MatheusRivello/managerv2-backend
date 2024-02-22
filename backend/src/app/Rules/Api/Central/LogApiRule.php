<?php

namespace App\Rules\Api\Central;

class LogApiRule
{
    public function rules($id, $connection = null)
    {
        return [
            "uri" => [
                'string',
                'max:255',
                'required',
            ],
            "method" => [
                'string',
                'required',
                'max:6',
            ],
            "params" => [
                'string',
            ],
            "api_key" => [
                'string',
                'max:40',
            ],
            "time" => [
                'int',
            ],
            "rtime" => [
                'numeric',
            ],
            "authorized" => [
                'string',
                'required',
                'max:1',
            ],
            "response_code" => [
                'int',
            ],
            "response" => [
                'string',
            ],
        ];
    }
}
