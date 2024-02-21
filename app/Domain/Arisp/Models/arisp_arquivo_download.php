<?php

namespace App\Domain\Arisp\Models;

use Illuminate\Database\Eloquent\Model;

class arisp_arquivo_download extends Model
{
    protected $table = 'arisp_arquivo_download';
    protected $primaryKey = 'id_arisp_arquivo_download';
    public $timestamps = false;
}
