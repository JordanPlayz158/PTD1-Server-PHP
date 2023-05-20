<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ExcludeController extends Controller
{
    public function excludeAttributes($exclude, $attributes) {
        if($exclude !== null) {
            $excludes = explode(',', $exclude);

            foreach ($excludes as $item) {
                $attributes = $attributes->reject(function ($value, $key) use ($item) {
                    return $value == $item;
                });
            }
        }

        return $attributes;
    }

    public function excludeRelations($exclude, $relations) {
        if($exclude !== null) {
            $excludes = explode(',', $exclude);

            foreach ($excludes as $item) {
                $relations = $relations->reject(function ($value, $key) use ($item) {
                    return str_starts_with($value, $item);
                });
            }
        }

        return $relations;
    }
}
