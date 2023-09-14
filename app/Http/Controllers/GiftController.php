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


    public function getGift($button)
    {
        $user = Auth::user();
        $dateCheck = Carbon::parse($user->last_used_dg)->addDay()->isBefore(Carbon::now('UTC'));

        if (!$dateCheck){
            return redirect()->back();
        }

        $gift  = $this->selectPrize($button);

        if (empty($gift)) {
            return redirect()->back();
        }

        if ($user->selectedSave()->money < $gift->cost) {
            return redirect()->back();
        }

        $updatedMoney = $user->selectedSave()->money - $gift->cost;
        $user->selectedSave()->update(['money' => $updatedMoney]);

        if ($gift->prize <= 20)
        {
            $updatedPTDCoins = $user->ptd_coins + $gift->prize;
            $user->ptd_coins = $updatedPTDCoins;

            $user->last_used_dg = Carbon::now('UTC');
            $user->save();

            return redirect()->back()->with([
                'prize' => "$gift->prize PTD Coins",
            ]);
        }
        else {
            $updatedCasinoCoins = $user->casino_coins + $gift->prize;
            $user->casino_coins = $updatedCasinoCoins;

            $user->last_used_dg = Carbon::now('UTC');
            $user->save();

            return redirect()->back()->with([
                'prize' => "$gift->prize Casino Coins",
            ]);
        }
    }
}
