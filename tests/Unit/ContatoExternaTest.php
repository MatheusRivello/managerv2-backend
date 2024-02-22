<?php

namespace Tests\Unit;

use Tests\TestCase;

class ContatoExternaTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testShow()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hZnYubm92b1wvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE2NjkxMzI1MzgsImV4cCI6MTY2OTEzNjEzOCwibmJmIjoxNjY5MTMyNTM4LCJqdGkiOiJDbE9FUnllYmRqcnBpVmg1Iiwic3ViIjoyMzksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.yKL9FGqh1gnk9q0xlWiRF1fsCwqn77CdZSOJb7_0TzQ'
        ])->get("http://afv.novo/api/ws/contato");

        $response->assertStatus(404);
    }
}
