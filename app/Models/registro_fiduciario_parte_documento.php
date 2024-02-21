<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    use Auth;

    class registro_fiduciario_parte_documento extends Model
    {
        protected $table = 'registro_fiduciario_parte_documento';
        protected $primaryKey = 'id_registro_fiduciario_parte_documento';
        public $timestamps = false;

        // Funções especiais
        public function insere($args)
        {
            $this->id_tipo_documento            = $args['id_tipo_documento'];
            $this->id_registro_fiduciario_parte = $args['id_registro_fiduciario_parte'];
            $this->codigo_tipo_documento        = $args['codigo_tipo_documento'];
            $this->nu_documento                 = $args['nu_documento'];
            $this->no_orgao_emissor             = $args['no_orgao_emissor'];
            $this->uf_orgao_emissor             = $args['uf_orgao_emissor'];
            $this->dt_expedicao                 = $args['dt_expedicao'];
            $this->id_usuario_cad               = Auth::User()->id_usuario;

            if ($this->save()) {
                return $this;
            } else {
                return false;
            }
        }

        // Funções de relacionamento
        public function parte()
        {
            return $this->belongsTo(registro_fiduciario_parte::class, 'id_registro_fiduciario_parte');
        }

        public function tipo_documento()
        {
            return $this->belongsTo(tipo_documento::class, 'id_tipo_documento');
        }

    }