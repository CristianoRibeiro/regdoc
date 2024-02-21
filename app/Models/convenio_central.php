<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    use Auth;

    class convenio_central extends Model
    {
        protected $table = 'convenio_central';
        protected $primaryKey = 'id_convenio_central';
        public $timestamps = false;

        // Funções especiais
        public function insere($args)
        {
            $this->codigo_convenio      = $args['codigo_convenio'];
            $this->no_convenio          = $args['no_convenio'];
            $this->abv_convenio         = $args['abv_convenio'];
            $this->de_convenio          = $args['de_convenio'];
            $this->nu_convenio          = $args['nu_convenio'];
            $this->nu_cpf_cnpj_convenio = $args['nu_cpf_cnpj_convenio'];
            $this->no_endereco          = $args['no_endereco'];
            $this->dt_ini_vigencia      = $args['dt_ini_vigencia'];
            $this->dt_fim_vigencia      = $args['dt_fim_vigencia'];
            $this->no_chave             = $args['no_chave'];
            $this->in_registro_ativo    = $args['in_registro_ativo'];
            $this->id_usuario_alt       = $args['id_usuario_alt'];
            $this->dt_alteracao         = $args['dt_alteracao'];
            $this->id_usuario_cad       = Auth::User()->id_usuario;

            if ($this->save()) {
                return $this;
            } else {
                return false;
            }
        }

        // Funções de relacionamento
        public function registro_fiduciario()
        {
            return $this->hasOne(registro_fiduciario::class, 'id_convenio_central');
        }

        public function tipo_documento()
        {
            return $this->hasOne(tipo_documento::class, 'id_convenio_central');
        }

        public function tipo_parte_registro_fiduciario()
        {
            return $this->hasOne(tipo_parte_registro_fiduciario::class, 'id_convenio_central');
        }


    }