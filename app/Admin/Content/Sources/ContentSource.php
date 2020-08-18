<?php


namespace App\Admin\Content\Sources;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface ContentSource extends Arrayable, Jsonable
{
    public function getUuid(): string;
}
