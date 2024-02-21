<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNoPjToRegistroFiduciarioCanalPdvParceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registro_fiduciario_canal_pdv_parceiro', function (Blueprint $table) {
            $table->string('no_pj', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registro_fiduciario_canal_pdv_parceiro', function (Blueprint $table) {
            $table->string('no_pj', 150);
        });
    }
}
