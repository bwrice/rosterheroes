<?php

namespace App\Http\Controllers;

use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Facades\Content;
use Illuminate\Http\Request;

class MinionContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $minionSources = Content::minions();
        $totalPages = (int) ceil($minionSources->count()/9);
        return view('admin.content.minions.index', [
            'contentType' => 'minions',
            'minions' => $minionSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'enemyTypes' => EnemyType::all(),
            'combatPositions' => CombatPosition::all(),
            'attacks' => Content::attacks(),
            'chestBlueprints' => Content::chestBlueprints()
        ]);
    }
}
