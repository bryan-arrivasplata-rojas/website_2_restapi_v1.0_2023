<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ExtensionModel;
use Illuminate\Support\Facades\Artisan;
class ExtensionTest extends TestCase
{
    /**
     * Prueba de creación de una extensión.
     *
     * @return void
     */
    
    public function testCreateExtension()
    {
        Artisan::call('migrate');

        $extensionData = [
            'name_extension' => 'test',
        ];

        $response = $this->post('/api/extension', $extensionData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Extension creado correctamente']);
    }

    /**
     * Prueba de obtención de una extensión.
     *
     * @return void
     */
    public function testGetExtension()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');

        $extension = ExtensionModel::latest('idExtension')->first();

        $response = $this->get('/api/extension/' . $extension->idExtension);

        $response->assertStatus(200)
            ->assertJson(['name_extension' => $extension->name_extension]);
    }

    /**
     * Prueba de eliminación de una extensión.
     *
     * @return void
     */
    public function testDeleteExtension()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        
        $extension = ExtensionModel::latest('idExtension')->first();

        $response = $this->delete('/api/extension/' . $extension->idExtension);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Extension eliminado correctamente']);
    }
}
