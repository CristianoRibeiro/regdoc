<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_documento_identificacao extends Model
{
    protected $table = 'tipo_documento_identificacao';
    protected $primaryKey = 'id_tipo_documento_identificacao';
    public $timestamps = false;
}
