<?php

namespace App\Http\Controllers;

use App\Facades\Content;
use Illuminate\Http\Request;

class ChestBlueprintContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $chestBlueprintSources = Content::chestBlueprints();
        $totalPages = (int) ceil($chestBlueprintSources->count()/9);
        return view('admin.content.chestBlueprints.index', [
            'contentType' => 'chest-blueprints',
            'chestBlueprints' => $chestBlueprintSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'itemBlueprints' => Content::itemBlueprints()
        ]);
    }
}
