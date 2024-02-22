<?php

namespace App\Rules\Api\Central;

class LogMobileRule
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
                'max:6',
                'required',
            ],
            "params" => [
                'string',
                'required',
            ],
            "api_key" => [
                'string',
                'required',
                'max:40',
            ],
            "time" => [
                'int',
                'required',
            ],
            "rtime" => [
                'numeric',
            ],
            "authorized" => [
                'string',
                'max:1',
            ],
            "response_code" => [
                'int',
            ],
            "response" => [
                'string',
                'max:4000000000',
            ],
        ];
    }
}
