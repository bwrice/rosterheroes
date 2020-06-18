<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\MerchantResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class LocalMerchantsController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        return MerchantResource::collection($squad->province->getMerchants());
    }
}
