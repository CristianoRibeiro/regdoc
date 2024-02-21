<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_endereco;

class registro_fiduciario_imovel extends Model
{
    protected $table = 'registro_fiduciario_imovel';
    protected $primaryKey = 'id_registro_fiduciario_imovel';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
    }
    public function registro_fiduciario_imovel_tipo()
    {
        return $this->belongsTo(registro_fiduciario_imovel_tipo::class, 'id_registro_fiduciario_imovel_tipo');
    }
    public function registro_fiduciario_imovel_localizacao()
    {
        return $this->belongsTo(registro_fiduciario_imovel_localizacao::class, 'id_registro_fiduciario_imovel_localizacao');
    }
    public function registro_fiduciario_imovel_livro()
    {
        return $this->belongsTo(registro_fiduciario_imovel_livro::class, 'id_registro_fiduciario_imovel_livro');
    }
    public function endereco()
    {
        return $this->belongsTo(registro_fiduciario_endereco::class, 'id_registro_fiduciario_endereco');
    }
}
