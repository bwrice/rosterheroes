<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Http\Resources\ProvinceResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::query()->with([
            'borders' => function(BelongsToMany $builder) {
                return $builder->select('uuid');
            }
        ])->get();
        return ProvinceResource::collection($provinces);
    }
}
