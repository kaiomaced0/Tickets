<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Um teste básico de exemplo.
     */
    public function test_aplicacao_retorna_resposta_bem_sucedida(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
