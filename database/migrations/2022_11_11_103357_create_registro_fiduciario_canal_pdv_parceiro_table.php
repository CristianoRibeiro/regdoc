<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroFiduciarioCanalPdvParceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_fiduciario_canal_pdv_parceiro', function (Blueprint $table) {
            $table->bigIncrements('id_registro_fiduciario_canal_pdv_parceiro');
            $table->integer('id_registro_fiduciario')->nullable();
            $table->foreign('id_registro_fiduciario')->references('id_registro_fiduciario')->on('registro_fiduciario')->restrictOnDelete()->restrictOnUpdate();
            $table->unsignedBigInteger('id_canal_pdv_parceiro')->nullable();
            $table->foreign('id_canal_pdv_parceiro')->references('id_canal_pdv_parceiro')->on('canal_pdv_parceiro')->restrictOnDelete()->restrictOnUpdate();
            $table->string('no_pj', 150);
            $table->integer('id_usuario_cad')->nullable();
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
        Schema::dropIfExists('registro_fiduciario_canal_pdv_parceiro');
    }
}
