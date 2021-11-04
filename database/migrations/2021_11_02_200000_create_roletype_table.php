<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRoleTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roleType', function (Blueprint $table) {
            $table->increments('id');
            $table->string('roleName');
        });

        // Insert some stuff
        DB::table('roleType')->insert(
            array('roleName' => 'Usuário')
        );
        DB::table('roleType')->insert(
            array('roleName' => 'Lojista')
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roleType');
    }
}
