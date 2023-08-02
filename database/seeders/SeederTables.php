<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExtensionModel;
use App\Models\UploadModel;
use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\TypeModel;
use App\Models\UsabilityModel;
use App\Models\FileModel;

class SeederTables extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExtensionModel::insert([
            ['name_extension' => 'otros'],
            ['name_extension' => 'png'],
            ['name_extension' => 'jpg'],
            ['name_extension' => 'pdf'],
            ['name_extension' => 'gif'],
        ]);
        UploadModel::insert([
            ['name_upload' => 'bryan','url_upload' => 'http://127.0.0.1:8000/uploads/bryan.png','idExtension' => '2'],
            ['name_upload' => 'mountains','url_upload' => 'http://127.0.0.1:8000/uploads/mountains.png','idExtension' => '2'],
            ['name_upload' => 'prueba1','url_upload' => 'http://127.0.0.1:8000/uploads/prueba1.png','idExtension' => '2'],
        ]);
        UserModel::insert([
            ['email' => 'bryanarrivasplata.rojas@gmail.com','password' => '123456789'],
        ]);
        ProfileModel::insert([
            [
                'name_profile' => 'Bryan Arrivasplata',
                'description_profile' => 'Descripción de prueba',
                'number' => '+51 997767771',
                'birthday' => '15 de Febrero',
                'idUser' => '1'
            ],
        ]);
        TypeModel::insert([
            ['name_type' => 'Otros','description_type' =>'Cual tipo de archivo en general'],
            ['name_type' => 'Aplicación Movil con Tecnologia GPS','description_type' => 'Desarrollo bajo arquitectura de microservicios'],
            ['name_type' => 'Desarrollo de Aplicación de Escritorio para Seguiminto de Personal','description_type' => 'Desarrollo con Electron y RestApi en Flask'],
            ['name_type' => 'Desarrollo de Website','description_type' => 'El desarrollo de la web actual con backend para los parametros'],
        ]);
        UsabilityModel::insert([
            ['name_usability' => 'Otros','description_usability' =>'Cual uso de archivo en general'],
            ['name_usability' => 'home','description_usability' => 'Todo lo relacionado a home'],
            ['name_usability' => 'about','description_usability' => 'Todo lo relacionado a about'],
            ['name_usability' => 'portafolio','description_usability' => 'Todo lo relacionado a portafolio'],
            ['name_usability' => 'language','description_usability' => 'Todo lo relacionado a language'],
            ['name_usability' => 'contact','description_usability' => 'Todo lo relacionado a contacto'],
        ]);
        FileModel::insert([
            ['name_file' => 'MyPerfil para Home','description_file' => 'Es mi foto de perfil','url_image' => 'http://127.0.0.1:8000/uploads/bryan.png','idUsability' => '2','idType' => '1','idUser' => '1'],
            ['name_file' => 'MyPerfil para About','description_file' => 'Es mi foto de perfil','url_image' => 'http://127.0.0.1:8000/uploads/bryan.png','idUsability' => '3','idType' => '1','idUser' => '1'],
            ['name_file' => 'Mountain','description_file' => 'Es mi foto de perfil','url_image' => 'http://127.0.0.1:8000/uploads/mountains.png','idUsability' => '1','idType' => '1','idUser' => '1'],
            ['name_file' => 'Prueba','description_file' => 'Es mi foto de perfil','url_image' => 'http://127.0.0.1:8000/uploads/prueba1.png','idUsability' => '1','idType' => '1','idUser' => '1'],
        ]);
    }
}
