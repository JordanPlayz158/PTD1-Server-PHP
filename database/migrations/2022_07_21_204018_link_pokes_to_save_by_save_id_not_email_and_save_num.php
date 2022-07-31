<?php

use App\Models\Save;
use App\Models\User;
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
        Schema::table('pokes', function (Blueprint $table) {
            $table->dropPrimary();
            $table->primary('id');
            $table->dropUnique('pokes_uuid_unique');
            $table->unique(['save_id', 'pId'], 'pokes_save_id_pId_unique');
        });

        Schema::dropColumns('pokes', ['email', 'num']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pokes', function (Blueprint $table) {
            $table->string('email');
            $table->tinyInteger('num', false, true);
        });

        Schema::table('pokes', function (Blueprint $table) {
            $table->dropPrimary();
            $table->primary(['email', 'num', 'pId']);
            $table->dropUnique('pokes_save_id_pId_unique');
            $table->unique('id', 'pokes_uuid_unique');
        });
    }
};
