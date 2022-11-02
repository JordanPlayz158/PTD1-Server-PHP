<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Save;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SavesController extends Controller {
    public function get(Request $request)
    {
        $saves = Auth::user()->saves();

        $relations = Collection::make(['pokemon', 'pokemon.offers', 'pokemon.requests']);
        $attributes = Collection::make(array_keys($saves->first()->getAttributes()));

        if(($exclude = $request->input('exclude')) !== null) {
            $excludes = explode(',', $exclude);

            foreach ($excludes as $item) {
                $attributes = $attributes->reject(function ($value, $key) use ($item) {
                    return $value == $item;
                });

                $relations = $relations->reject(function ($value, $key) use ($item) {
                    return str_starts_with($value, $item);
                });
            }
        }

        // TODO:
        //  BUG:
        //   This line (for some reason) only returns 1 save rather than all saves causing the below
        //   bug and messes up exclude logic as it only returns all 3 only with no special params
        $saves = $saves->with($relations->undot()->toArray())->select($attributes->toArray())->get();

        // TODO:
        //  BUG:
        //   When the `save` parameter is sent, it returns a blank array, this is due to a bug with
        //   save fetching where it only gets one entry, refer to above BUG to fix this one
        if(($save = $request->input('save')) !== null) {
            $nums = [0, 1, 2];

            $nums = array_diff($nums, [$save]);

            foreach ($nums as $num) {
                $saves->pull($num);
            }
        }

        $saves->each(function (Save $save) {
            $save->nickname = $save->nickname ?? 'Satoshi';
        });

        return $saves->toArray();
    }
}
