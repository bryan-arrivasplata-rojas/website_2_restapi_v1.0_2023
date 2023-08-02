<?php

namespace App\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileModel extends Model
{
    use HasFactory;
    protected $table = 'profile'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idProfile'; //Nombre del identificador
    protected $fillable = [
        'idProfile',
        'name_profile',
        'description_profile',
        'number',
        'birthday',
        'idUser'
    ];
    public function user()
    {
        return $this->hasOne(UserModel::class, 'idUser');
    }
}
