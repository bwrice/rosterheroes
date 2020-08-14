<?php

namespace App\Http\Controllers;

use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Facades\Content;
use Illuminate\Http\Request;

class ItemBlueprintContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $itemBlueprintSources = Content::itemBlueprints();
        $totalPages = (int) ceil($itemBlueprintSources->count()/9);
        return view('admin.content.itemBlueprints.index', [
            'contentType' => 'item-blueprints',
            'itemBlueprints' => $itemBlueprintSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'itemBases' => ItemBase::all(),
            'itemClasses' => ItemClass::all(),
            'itemTypes' => ItemType::all(),
            'attacks' => Content::attacks(),
            // TODO: use content for materials and enchantments
            'materials' => Material::all(),
            'enchantments' => Enchantment::all()
        ]);
    }
}
