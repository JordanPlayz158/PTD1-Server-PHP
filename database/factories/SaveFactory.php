<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Save>
 */
class SaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // called levelUnlocked (in SWF)
            'advanced' => 0,
            'advanced_a' => 0,
            'nickname' => 'Satoshi',
            'badges' => 0,
            'avatar' => 'none',
            // called haveFlash (in SWF), assuming it's talking about the Flash TM
            'classic' => 0,
            // split by '|' and called extraInfo
            'classic_a' => '',
            // called clevelCompleted (in SWF)
            'challenge' => 0,
            'money' => 50,
            'npcTrade' => 0,
            'shinyHunt' => 0,
            'version' => 2
        ];
    }
}
