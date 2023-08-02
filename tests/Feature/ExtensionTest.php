<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ExtensionModel;
use Illuminate\Support\Facades\Artisan;

class ExtensionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method of ExtensionController.
     *
     * @return void
     */
    public function testIndex()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');

        $response = $this->get('/api/extension');

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'idExtension',
                'name_extension',
            ],
        ]);
    }

    /**
     * Test store method of ExtensionController.
     *
     * @return void
     */
    public function testStore()
    {
        $extensionData = [
            'name_extension' => 'prueba',
        ];

        $response = $this->post('/api/extension', $extensionData);

        //$response->assertStatus(200);
        $response->assertJson([
            'message' => 'Extension creado correctamente',
        ]);
    }

    // Agrega aquí otros métodos de prueba para los demás métodos del controlador

    // ...

}
