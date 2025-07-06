<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rol')->default('gestor');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
