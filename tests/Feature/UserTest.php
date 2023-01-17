<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class UserTest extends TestCase
{
    /**
     * Vamos a terstar el registro de usuarios 
     */
    public function test_create(){
        Artisan::call('migrate');
        $carga=$this->get(route('empleado.index'));
        $carga->assertStatus(200)->assertSee('Registrarse');
    }
    
}
