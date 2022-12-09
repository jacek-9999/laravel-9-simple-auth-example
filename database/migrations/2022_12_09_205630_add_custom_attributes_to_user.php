<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyText('role');
            $table->boolean('active');
            $table->boolean('verified');
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('active');
            $table->dropColumn('verified');
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};