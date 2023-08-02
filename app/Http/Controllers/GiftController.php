<?php

namespace App\Http\Controllers;

use App\Models\DailyGift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function selectPrize($button)
    {
        // Retrieve all prizes from the database
        $gifts  = DailyGift::where('button', $button)->get();

        $totalProbability = $gifts->sum('percentage');

        // Normalize probabilities to ensure they add up to 1
        $normalizedGifts = $gifts->map(function ($gift) use ($totalProbability) {
            return $gift->percentage / $totalProbability;
        });

        $randomNumber = mt_rand() / mt_getrandmax();

        $sum = 0;

        foreach ($normalizedGifts as $key => $gift) {
            $sum += $gift;

            if ($randomNumber < $sum || ($randomNumber == 1 && $sum == 1)) {
                return $gifts[$key];
            }
        }
    }
    

    public function GetGift($button)
    {
        if (Carbon::parse(Auth::user()->last_used_gc)->addDay()->isBefore(Carbon::now('UTC'))) {
            return redirect()->back();
        }
        
        $gift  = $this->selectPrize($button);

        if (empty($gift)) {
            return redirect()->back();
        }

        if (Auth::user()->selectedSave()->money < $gift->cost) {
            return redirect()->back();
        }

        $updatedMoney = Auth::user()->selectedSave()->money - $gift->cost;
        Auth::user()->selectedSave()->update(['money' => $updatedMoney]);

        if ($gift->prize <= 20)
        {
            $updatedPTDCoins = Auth::user()->ptd_coins + $gift->prize;
            Auth::user()->ptd_coins = $updatedPTDCoins;

            Auth::user()->last_used_dg = Carbon::now('UTC');
            Auth::user()->save();

            return redirect()->back()->with([
                'prize' => "$gift->prize PTD Coins",
            ]);
        }
        else {
            $updatedCasinoCoins = Auth::user()->casino_coins + $gift->prize;
            Auth::user()->casino_coins = $updatedCasinoCoins;

            Auth::user()->last_used_dg = Carbon::now('UTC');
            Auth::user()->save();
            
            return redirect()->back()->with([
                'prize' => "$gift->prize Casino Coins",
            ]);
        }
    }
}
