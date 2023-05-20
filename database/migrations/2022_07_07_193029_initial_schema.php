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
        if(!Schema::hasTable('accounts')) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->string('email', 50)->nullable(false)->primary();
                $table->string('pass', 255)->nullable(false);
                $table->string('accNickname', 255)->nullable(true);
                $table->string('dex1', 151)->nullable(true);
                $table->string('dex1Shiny', 151)->nullable(true);
                $table->string('dex1Shadow', 151)->nullable(true);
            });
        }

        if(!Schema::hasTable('saves')) {
            Schema::create('saves', function (Blueprint $table) {
                $table->bigInteger('uuid', false, true)->nullable(false)->unique();
                $table->string('email', 50)->nullable(false);
                $table->tinyInteger('num', false, true)->nullable(false);
                $table->tinyInteger('advanced', false, true);
                $table->tinyInteger('advanced_a', false, true);
                $table->string('nickname', 255);
                $table->tinyInteger('badges', false, true);
                $table->string('avatar', 4);
                $table->tinyInteger('classic', false, true);
                $table->string('classic_a', 255);
                $table->tinyInteger('challenge', false, true);
                $table->integer('money', false, true);
                $table->tinyInteger('npcTrade', false, true);
                $table->tinyInteger('shinyHunt', false, true);
                $table->tinyInteger('version', false, true);
                $table->longText('items');

                $table->primary(['email', 'num']);
            });

            Schema::table('saves', function (Blueprint $table) {
                $table->bigInteger('uuid', true, true)->change();
            });
        }

        Schema::table('saves', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid', true)->change();
        });

        if(!Schema::hasTable('pokes')) {
            Schema::create('pokes', function (Blueprint $table) {
                $table->bigInteger('uuid', false, true)->nullable(false)->unique();
                $table->string('email', 50)->nullable(false);
                $table->tinyInteger('num', false, true)->nullable(false);
                $table->mediumInteger('id', false, true)->nullable(false);
                $table->mediumInteger('pNum', false, true);
                $table->string('nickname', 255);
                $table->mediumInteger('exp', false, true);
                $table->tinyInteger('lvl', false, true);
                $table->smallInteger('m1', false, true);
                $table->smallInteger('m2', false, true);
                $table->smallInteger('m3', false, true);
                $table->smallInteger('m4', false, true);
                $table->smallInteger('ability', false, true);
                $table->tinyInteger('mSel', false, true);
                $table->tinyInteger('targetType', false, true);
                $table->string('tag', 3);
                $table->string('item', 3);
                $table->string('owner', 255);
                $table->mediumInteger('pos', false, true);
                $table->tinyInteger('shiny', false, true);

                $table->primary(['email', 'num', 'id']);
            });

            Schema::table('pokes', function (Blueprint $table) {
                $table->bigInteger('uuid', true, true)->change();
            });
        }

        Schema::table('pokes', function (Blueprint $table) {
            $table->unsignedBigInteger('uuid', true)->change();
        });

        if(!Schema::hasTable('achievements')) {
            Schema::create('achievements', function (Blueprint $table) {
                $table->string('email', 50)->nullable(false)->primary();
                $table->char('one', 4)->nullable(true);
                $table->tinyInteger('two', false, true)->nullable(true);
                $table->tinyInteger('three', false, true)->nullable(true);
                $table->tinyInteger('four', false, true)->nullable(true);
                $table->tinyInteger('five', false, true)->nullable(true);
                $table->tinyInteger('six', false, true)->nullable(true);
                $table->tinyInteger('seven', false, true)->nullable(true);
                $table->tinyInteger('eight', false, true)->nullable(true);
                $table->tinyInteger('nine', false, true)->nullable(true);
                $table->tinyInteger('ten', false, true)->nullable(true);
                $table->tinyInteger('eleven', false, true)->nullable(true);
                $table->tinyInteger('twelve', false, true)->nullable(true);
                $table->tinyInteger('thirteen', false, true)->nullable(true);
                $table->tinyInteger('fourteen', false, true)->nullable(true);
            });
        }

        if(!Schema::hasTable('logs')) {
            Schema::create('logs', function (Blueprint $table) {
                $table->integer('time', false, true);
                $table->string('ip', 255);
                $table->longText('post_data');
                $table->longText('response');
            });
        }

        if(!Schema::hasTable('trades')) {
            Schema::create('trades', function (Blueprint $table) {
                $table->string('email', 50)->nullable(false);
                $table->tinyInteger('num', false, true)->nullable(false);
                $table->mediumInteger('id', false, true)->nullable(false);

                $table->primary(['email', 'num', 'id']);
            });
        }

        if(!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->bigInteger('id', true, true)->nullable(false);
                $table->bigInteger('offerSave', false, true);
                $table->longText('offerIds');
                $table->bigInteger('requestSave', false, true);
                $table->longText('requestIds');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('saves');
        Schema::dropIfExists('pokes');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('trades');
        Schema::dropIfExists('offers');
    }
};
