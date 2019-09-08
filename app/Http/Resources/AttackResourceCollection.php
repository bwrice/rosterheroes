<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasAttacks;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttackResourceCollection extends ResourceCollection
{

    public $collects = AttackResource::class;

    /**
     * @var HasAttacks
     */
    protected $hasAttacks;

    public function __construct($resource, HasAttacks $hasAttacks = null)
    {
        parent::__construct($resource);
        $this->hasAttacks = $hasAttacks;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function (AttackResource $attackResource) {
                return $attackResource->setHasAttacks($this->hasAttacks);
            })->toArray();
    }
}
