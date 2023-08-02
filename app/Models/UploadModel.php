<?php

namespace App\Models;

use App\Models\ExtensionModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadModel extends Model
{
    use HasFactory;
    protected $table = 'upload'; // Nombre de la tabla
    public $timestamps = false; // Desactivar los campos timestamps
    protected $primaryKey = 'idUpload'; //Nombre del identificador
    protected $fillable = [
        'idUpload',
        'name_upload',
        'url_upload',
        'created_at',
        'idExtension'
    ];
    public function extension()
    {
        return $this->belongsTo(ExtensionModel::class, 'idExtension');
    }
}
