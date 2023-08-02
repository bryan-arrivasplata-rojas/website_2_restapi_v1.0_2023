<?php

namespace App\Models;

use App\Models\ProfileModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'user'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idUser'; //Nombre del identificador
    protected $fillable = [
        'idUser',
        'email',
        'password',
    ];
    public function profile()
    {
        return $this->hasOne(ProfileModel::class, 'idUser');
    }
}
