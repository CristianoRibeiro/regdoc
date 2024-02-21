<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_imovel_livro extends Model
{
    protected $table = 'registro_fiduciario_imovel_livro';

    protected $primaryKey = 'id_registro_fiduciario_imovel_livro';

    public $timestamps = false;
}