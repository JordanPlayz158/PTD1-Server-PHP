<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function getGift($button)
    {
        
        $user = User::find(Auth::user()->id);
        $save = $user->selectedSave();
        $randomNumber = mt_rand(0, 1);          
        $currentDate = Carbon::parse(Carbon::now())->format('Y-m-d');

        if ($user->last_used_dg === $currentDate)
        {
            return redirect()->back();
        }

        // Define the gift costs for each button
        $giftCosts = [
            1 => 1000,
            2 => 10000,
            3 => 100000
        ];

        // Define the gift prizes for each button
        $giftPrizes = [
            1 => [1000, 10000],
            2 => [10000, 25000],
            3 => [50000, 100000],
        ];
        // Retrieve the user's / save's info
        $money = $save->money;
        $casinoCoins = $user->casino_coins;

        // Check if the profile has enough money to buy the gift
        $giftCost = $giftCosts[$button];
        if ($money < $giftCost) {
            return redirect()->back()->with('error', 'Not enough coins to buy the gift');
        }

        // Update the profile's money after buying the daily gift
        $updatedMoney = $money - $giftCost;
        $save->update(['money' => $updatedMoney]);

        // Generate a random number between 0 and 1
        $prize = $giftPrizes[$button][$randomNumber];

        // Update the user's casino coins after buying the daily gift
        $updatedCasinoCoins = $casinoCoins + $prize;
        
        $user->update(['casino_coins' => $updatedCasinoCoins, 'last_used_dg' => Carbon::now()]);

        return redirect()->back()->with([
        'prize' => $prize,
    ]);
    }
}
