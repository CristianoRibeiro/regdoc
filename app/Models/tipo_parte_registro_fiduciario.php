<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    use Auth;

    class tipo_parte_registro_fiduciario extends Model
    {
        protected $table = 'tipo_parte_registro_fiduciario';
        protected $primaryKey = 'id_tipo_parte_registro_fiduciario';
        public $timestamps = false;

        // Funções especiais
        public function insere($args)
        {
            $this->codigo_tipo_parte_registro_fiduciario = $args['codigo_tipo_parte_registro_fiduciario'];
            $this->no_tipo_parte_registro_fiduciario     = $args['no_tipo_parte_registro_fiduciario'];
            $this->abv_tipo_parte_registro_fiduciario    = $args['abv_tipo_parte_registro_fiduciario'];
            $this->in_registro_ativo                     = $args['in_registro_ativo'];
            $this->nu_ordem                              = $args['nu_ordem'];
            $this->id_convenio_central                   = $args['id_convenio_central'];
            $this->id_usuario_cad                        = Auth::User()->id_usuario;

            if ($this->save()) {
                return $this;
            } else {
                return false;
            }
        }

        // Funções de relacionamento
        public function registro_fiduciario_parte()
        {
            return $this->hasOne(registro_fiduciario_parte::class, 'id_tipo_parte_registro_fiduciario');
        }

        public function convenio_central()
        {
            return $this->belongsTo(convenio_central::class, 'id_convenio_central');
        }


    }