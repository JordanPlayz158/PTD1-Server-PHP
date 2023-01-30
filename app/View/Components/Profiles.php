<?php

namespace App\View\Components;

use App\Models\Save;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\Component;

class Profiles extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $saves = Auth::user()->saves()->get(['num']);

        while (sizeof($saves) < 3) {
            $save = Save::factory()->make();

            $save->num = $this->nextAvailableSaveNumber($saves);

            $saves->add((object) $save);
        }

        $saves->each(function (Save $save) {
            $save->nickname = $save->nickname ?? 'Satoshi';
        });

        $saveNum = Auth::getSession()->get('save', 0);

        $saves = $saves->reject(function ($value, $key) use ($saveNum) {
            return $value->num == $saveNum;
        });

        return view('components.profiles', ['saves' => $saves]);
    }

    public static function nextAvailableSaveNumber($saves) {
        $validNums = [0, 1, 2];
        $nums = [];

        foreach($saves as $save) {
            $nums[] = $save->num;
        }

        return array_values(array_diff($validNums, $nums))[0];
    }
}
