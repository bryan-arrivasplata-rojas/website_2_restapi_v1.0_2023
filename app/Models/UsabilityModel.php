<?php

namespace App\Models;
use App\Models\FileModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsabilityModel extends Model
{
    use HasFactory;
    protected $table = 'usability'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idUsability'; //Nombre del identificador
    protected $fillable = [
        'idUsability',
        'name_usability',
        'description_usability',
        'position_usability',
    ];
    public function file()
    {
        return $this->hasMany(FileModel::class, 'idUsability');
    }
}