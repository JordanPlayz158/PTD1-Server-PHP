<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\ExcludeController;
use App\Models\Save;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SavesController extends ExcludeController {
    public function get(Request $request)
    {
        $saves = Auth::user()->saves();

        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['pokemon', 'pokemon.offers', 'pokemon.requests']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys($saves->first()->getAttributes())));

        // This line is required as Laravel appears to lazy load the HasMany relationship models
        // In order to load them and have the with and select work properly, we need to iterate
        // over all the data, so it fetches it before those statements
        $saves->each(function (Save $save) {});

        $saves = $saves->with($relations->undot()->toArray())->select($attributes->toArray())->get();

        $saves->each(function (Save $save) {
            $save->nickname = $save->nickname ?? 'Satoshi';
        });

        return $saves->toArray();
    }

    public function getSpecificSave(Request $request, int $num)
    {
        $save = Auth::user()->saves()->where('num', '=', $num);

        $relations = $this->excludeRelations($request->input('exclude'), Collection::make(['pokemon', 'pokemon.offers', 'pokemon.requests']));
        $attributes = $this->excludeAttributes($request->input('exclude'), Collection::make(array_keys($save->first()->getAttributes())));

        // This line is required as Laravel appears to lazy load the HasMany relationship models
        // In order to load them and have the with and select work properly, we need to iterate
        // over all the data, so it fetches it before those statements
        $save = $save->with($relations->undot()->toArray())
            ->select($attributes->toArray())->get()->first();

        $save->nickname = $save->nickname ?? 'Satoshi';

        return $save->toArray();
    }

    public function postSpecificSave(Request $request, int $num)
    {
        // This line is required as Laravel appears to lazy load the HasMany relationship models
        // In order to load them and have the with and select work properly, we need to iterate
        // over all the data, so it fetches it before those statements
        $save = Auth::user()->saves()->where('num', '=', $num);

        $prohibited = ['id', 'user_id', 'created_at', 'updated_at'];

        foreach ($prohibited as $prohibit) {
            if($request->has($prohibit)) {
                return ['success' => false, 'error' => "The $prohibit column cannot be changed"];
            }
        }

        if(!$save->update($request->toArray())) {
            return ['success' => false, 'error' => 'An unknown error occurred while trying to update the save'];
        }

        return ['success' => true];
    }
}
