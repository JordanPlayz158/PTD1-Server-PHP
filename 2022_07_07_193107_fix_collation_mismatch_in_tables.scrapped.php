<?php

use App\Models\Save;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        $tableSuffix = '_new';
        $out = new ConsoleOutput();


        foreach (DB::select('SHOW TABLE STATUS') as $tableInfo) {
            if($tableInfo->Collation === 'utf8mb4_unicode_ci') {
                continue;
            }

            $tableName = $tableInfo->Name;

            $idColumn = 'id';
            switch ($tableName) {
                case 'achievements':
                case 'password_resets':
                case 'trades':
                    $idColumn = 'email';
                    break;
                case 'logs':
                    $idColumn = 'time';
            }

            Schema::create($tableName . $tableSuffix, function (Blueprint $table) use ($out, $tableName) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->engine = 'InnoDB';

                $ignorePrimary = [];
                foreach (DB::select('SHOW COLUMNS FROM ' . $tableName) as $column) {
                    $columnName = $column->Field;
                    $columnTypeProperties = explode(' ', $column->Type);

                    $columnType = $columnTypeProperties[0];
                    if (str_contains($columnType, 'char')) {
                        $length = $this->getColumnSize($columnType);

                        if (str_contains($columnType, 'varchar')) {
                            $columnDefinition = $table->string($columnName, $length);
                        } else {
                            $columnDefinition = $table->char($columnName, $length);
                        }
                    } else if(str_contains($columnType, 'bigint')) {
                        $columnDefinition = $table->bigInteger($columnName, false, false);
                    } else if(str_contains($columnType, 'mediumint')) {
                        $columnDefinition = $table->mediumInteger($columnName, false, false);
                    } else if(str_contains($columnType, 'smallint')) {
                        $columnDefinition = $table->smallInteger($columnName, false, false);
                    } else if(str_contains($columnType, 'longtext')) {
                        $columnDefinition = $table->longText($columnName);
                    } else {
                        $columnDefinition = $table->addColumn(Schema::getColumnType($tableName, $columnName),
                            $columnName);
                    }

                    $columnDefinition->nullable($column->Null === 'YES');

                    $columnTypeProperties = array_merge($columnTypeProperties, explode(' ', $column->Extra));

                    foreach ($columnTypeProperties as $columnTypeProperty) {
                        switch ($columnTypeProperty) {
                            // This generates a primary key by default which causes issue with double primary keys
                            // Find a way to remedy this issue.
                            case 'auto_increment':
                                $columnDefinition->autoIncrement();
                                $ignorePrimary[] = $columnName;
                                break;
                            case 'unsigned':
                                $columnDefinition->unsigned();
                        }
                    }
                }

                // Iterate through indexes
                $multiColumnIndex = [];
                foreach(DB::select('SHOW INDEXES FROM ' . $tableName) as $index) {
                    // Add each index's column to $multiColumnIndexes array
                    $arraySize = sizeof($multiColumnIndex);

                    if($arraySize > 0) {
                        $lastIndex = $multiColumnIndex[$arraySize - 1];
                        $lastIndexKeyName = $lastIndex->Key_name;

                        // If the index has the same name as the previous one, add the column to the array
                        if($lastIndexKeyName === $index->Key_name) {
                            $multiColumnIndex[] = $index;
                        } else {
                            if($lastIndexKeyName === 'PRIMARY') {
                                if(!in_array($lastIndex->Column_name, $ignorePrimary)) {
                                    $table->primary($this->columnNamesFromIndexArray($multiColumnIndex));
                                }
                            } else {
                                if($index->Non_unique === 1) {
                                    $table->index($this->columnNamesFromIndexArray($multiColumnIndex), $lastIndexKeyName);
                                } else {
                                    $table->unique($this->columnNamesFromIndexArray($multiColumnIndex), $lastIndexKeyName);
                                }
                            }

                            // Repeat until new index name, then, on new index name
                            // Make index with $multiColumnIndex as argument and clear the array and repeat
                            $multiColumnIndex = [];
                        }
                    } else {
                        $multiColumnIndex[] = $index;
                    }
                }

                $arraySize = sizeof($multiColumnIndex);

                // In the probable case where the array will end with a multi column index
                // The foreach will return before we can make that last index, so have a check if
                // The array is empty, if not, make an index with the remaining array and reset it
                if($arraySize > 0) {
                    $key = $multiColumnIndex[$arraySize - 1];
                    $keyName = $key->Key_name;
                    //$out->writeln(print_r($multiColumnIndex, true));

                    $out->writeln(print_r($ignorePrimary, true));

                    if($keyName === 'PRIMARY') {
                        if(!in_array($key->Column_name, $ignorePrimary)) {
                            $table->primary($this->columnNamesFromIndexArray($multiColumnIndex));
                        }
                    } else {
                        if($key->Non_unique === 1) {
                            $table->index($this->columnNamesFromIndexArray($multiColumnIndex), $keyName);
                        } else {
                            $table->unique($this->columnNamesFromIndexArray($multiColumnIndex), $keyName);
                        }
                    }

                    $multiColumnIndex = [];
                }

                //$out->writeln(print_r($table, true));
            });

            /*DB::table($tableName)->chunkById(100, function ($rows) {
                foreach ($rows as $row) {

                }
            }, $idColumn);*/
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

    public function columnNamesFromIndexArray(array $arrayOfIndexes): array
    {
        $indexColumnNames = [];

        foreach($arrayOfIndexes as $index) {
            $indexColumnNames[] = $index->Column_name;
        }

        return $indexColumnNames;
    }

    public function getColumnSize(string $columnType) : int {
        $lengthStart = strpos($columnType, '(') + 1;
        $lengthEnd = strpos($columnType, ')') - $lengthStart;
        return intval(substr($columnType, $lengthStart, $lengthEnd));
    }
};
