<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanalPdvParceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canal_pdv_parceiro', function (Blueprint $table) {
            $table->bigIncrements('id_canal_pdv_parceiro');
            $table->string('nome_canal_pdv_parceiro', 255);
            $table->string('email_canal_pdv_parceiro', 255);
            $table->string('codigo_canal_pdv_parceiro', 255);
            $table->string('parceiro_canal_pdv_parceiro', 255);
            $table->string('cnpj_canal_pdv_parceiro', 14);
            $table->char('in_canal_pdv_parceiro_ativo', 1)->default('S');
            $table->integer('id_usuario_cad');
            $table->foreign('id_usuario_cad')->references('id_usuario')->on('usuario')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamp('dt_cadastro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canal_pdv_parceiro');
    }
}
