<?php

namespace App\Models;
use App\Models\FileModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    use HasFactory;
    protected $table = 'type'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idType'; //Nombre del identificador
    protected $fillable = [
        'idType',
        'name_type',
        'description_type',
        'position_type',
    ];
    public function file()
    {
        return $this->hasMany(FileModel::class, 'idType');
    }
}