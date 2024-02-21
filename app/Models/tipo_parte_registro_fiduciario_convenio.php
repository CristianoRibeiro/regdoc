<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    use Auth;

    class tipo_parte_registro_fiduciario_convenio extends Model
    {
        protected $table = 'tipo_parte_registro_fiduciario_convenio';
        protected $primaryKey = 'id_tipo_parte_registro_fiduciario_convenio';
        public $timestamps = false;

        // Funções especiais
        public function insere($args)
        {

            //AGUARDANDO A TABELA SER CRIADA

            if ($this->save()) {
                return $this;
            } else {
                return false;
            }
        }

        /*
        // Funções de relacionamento
        public function registro_fiduciario_parte()
        {
            return $this->hasOne(registro_fiduciario_parte::class, 'id_tipo_parte_registro_fiduciario_convenio');
        }

        public function convenio_central()
        {
            return $this->belongsTo(convenio_central::class, 'id_convenio_central');
        }
        */

    }