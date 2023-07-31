<?php

namespace App\Http\Controllers;

use App\Models\DailyGift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function GetGift($button)
    {
        if (Carbon::parse(Auth::user()->last_used_gc)->addDay()->isBefore(Carbon::now('UTC'))) {
            return redirect()->back();
        }
        
        $buttonToGiftIdMapping = [
            1 => [1, 2], // Map button 1 to gift with id 1 and 2
            2 => [3, 4], // Map button 2 to gift with id 3 and 4
            3 => [5, 6], // Map button 3 to gift with id 5 and 6
        ];

        $gift = DailyGift::whereIn('id', $buttonToGiftIdMapping[$button])->inRandomOrder()->first();
        
        if (Auth::user()->selectedSave()->money < $gift->cost) {
            return redirect()->back();
        }

        if (empty($buttonToGiftIdMapping[$button])) {
            return redirect()->back();
        }

        $updatedMoney = Auth::user()->selectedSave()->money - $gift->cost;
        Auth::user()->selectedSave()->update(['money' => $updatedMoney]);

        $updatedCasinoCoins = Auth::user()->casino_coins + $gift->prize;

        Auth::user()->casino_coins = $updatedCasinoCoins;
        Auth::user()->last_used_dg = Carbon::now('UTC');
        Auth::user()->save();

        return redirect()->back()->with([
            'prize' => $gift->prize,
        ]);
    }
}
