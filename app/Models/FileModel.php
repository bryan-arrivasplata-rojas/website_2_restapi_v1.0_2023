<?php

namespace App\Models;

use App\Models\TypeModel;
use App\Models\UsabilityModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileModel extends Model
{
    use HasFactory;
    protected $table = 'file'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idFile'; //Nombre del identificador
    protected $fillable = [
        'idFile',
        'name_file',
        'description_file',
        'url_image',
        'url_video',
        'url_visit',
        'url_document',
        'url_download',
        'url_icon',
        'language',
        'nivel',
        'position_file',
        'idUsability',
        'idType',
        'idUser',
    ];
    public function type()
    {
        return $this->belongsTo(TypeModel::class, 'idType');
    }
    public function usability()
    {
        return $this->belongsTo(UsabilityModel::class, 'idUsability');
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'idUser');
    }
}
