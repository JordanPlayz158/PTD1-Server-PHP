<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('save_items', function (Blueprint $table) {
            $table->unsignedBigInteger('save_id')->nullable(false);
            $table->unsignedInteger('item')->nullable(false);
        });

        $rowNum = DB::table('saves')->count('id');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        foreach (DB::table('saves')->select(['id', 'items'])->lazyById(1000, 'id') as $save) {
            foreach (unserialize($save->items) as $item) {
                DB::table('save_items')->insert([
                    'save_id' => $save->id,
                    'item' => $item,
                ]);
            }

            $rowInsertCounter++;
            $progress->advance();
        }

        $progress->finish();

        Log::info($rowInsertCounter . ' items inserted from ' . $rowNum . ' saves');

        Schema::table('saves', function (Blueprint $table) {
            $table->dropColumn('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saves', function (Blueprint $table) {
            $table->longText('items');
        });

        $rowNum = DB::table('save_items')->count('save_id');
        $rowInsertCounter = 0;

        $output = new ConsoleOutput();
        $output->writeln('');
        $progress = new ProgressBar($output, $rowNum);
        $progress->start();

        $previousSaveId = -1;
        $items = [];
        foreach (DB::table('save_items')->lazyById() as $saveItem) {
            $saveId = $saveItem->save_id;

            if($previousSaveId !== $saveId && $previousSaveId !== -1) {
                DB::table('saves')->insert([
                    'items' => serialize($items)
                ]);

                $items = [];
            }

            $items[] = $saveItem->item;

            $rowInsertCounter++;
            $progress->advance();

            $previousSaveId = $saveId;
        }

        $progress->finish();

        Log::info($rowInsertCounter . ' items serialized from ' . $rowNum . ' save_items');

        Schema::drop('save_items');
    }
};
