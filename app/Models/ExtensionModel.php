<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionModel extends Model
{
    use HasFactory;
    protected $table = 'extension'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idExtension'; //Nombre del identificador
    protected $fillable = [
        'idExtension',
        'name_extension',
    ];
}
