<?php
 
namespace Database\Seeders;

use App\Models\GameCornerPokemon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
 
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
 
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            GameCornerPokemonSeeder::class,
            DailyGiftSeeder::class
        ]);
    }
}