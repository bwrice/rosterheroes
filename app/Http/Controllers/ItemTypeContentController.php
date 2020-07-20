<?php

namespace App\Http\Controllers;

use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
use App\Domain\Models\TargetPriority;
use App\Facades\Content;
use Illuminate\Http\Request;

class ItemTypeContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $itemTypesSources = Content::itemTypes();
        $totalPages = (int) ceil($itemTypesSources->count()/9);
        return view('admin.content.itemTypes.index', [
            'contentType' => 'item-types',
            'itemTypes' => $itemTypesSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'attacks' => Attack::all(),
            'itemBases' => ItemBase::all()
        ]);
    }


    /**
     * @param $attackUuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * TODO add associated view and route
     */
    public function edit($attackUuid)
    {
        $itemTypeSource =Content::attacks()->first(function (ItemTypeSource $itemTypeSource) use ($attackUuid) {
            return $attackUuid === (string) $itemTypeSource->getUuid();
        });

        return view('admin.content.itemTypes.edit', [
            'itemTypeSource' => $itemTypeSource
        ]);
    }
}
