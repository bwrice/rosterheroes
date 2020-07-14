<?php

namespace App\Http\Controllers;

use App\Admin\Content\Actions\CreateAttack;
use App\Admin\Content\Sources\AttackSource;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Facades\Content;
use Illuminate\Http\Request;

class AttackContentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?: 1;
        $attackSources = Content::attacks();
        $totalPages = (int) ceil($attackSources->count()/9);
        return view('admin.content.attacks.index', [
            'attacks' => $attackSources->forPage($page, 9),
            'page' => $page,
            'totalPages' => $totalPages,
            'combatPositions' => CombatPosition::all(),
            'targetPriorities' => TargetPriority::all(),
            'damageTypes' => DamageType::all()
        ]);
    }

    public function create()
    {
        return view('admin.content.attacks.create', [
            'combatPositions' => CombatPosition::all(),
            'targetPriorities' => TargetPriority::all(),
            'damageTypes' => DamageType::all()
        ]);
    }

    public function edit($attackUuid)
    {
        $attackSource =Content::attacks()->first(function (AttackSource $attackSource) use ($attackUuid) {
            return $attackUuid === (string) $attackSource->getUuid();
        });

        return view('admin.content.attacks.edit', [
            'attackSource' => $attackSource,
            'combatPositions' => CombatPosition::all(),
            'targetPriorities' => TargetPriority::all(),
            'damageTypes' => DamageType::all()
        ]);
    }

    public function store(Request $request, CreateAttack $createAttack)
    {
        $createAttack->execute($this->buildAttackSourceFromRequest($request));

        $request->session()->flash('success', $request->name . ' created');
        return redirect('admin/content/attacks/create');
    }

    protected function buildAttackSourceFromRequest(Request $request)
    {
        $request->validate([
            'name' => 'required', 'string', 'unique:attacks',
            'tier' => 'required', 'integer', 'between:1,10',
            'attackerPosition' => 'required', 'exists:combat_positions,id',
            'targetPosition' => 'required', 'exists:combat_positions,id',
            'targetPriority' => 'required', 'exists:target_priorities,id',
            'damageType' => 'required', 'exists:damage_types,id',
            'targetsCount' => function ($attribute, $value, $fail) use ($request) {
                /** @var DamageType $damageType */
                $damageType = DamageType::query()->find($request->damageType);
                if ($damageType->name === DamageType::FIXED_TARGET) {
                    if (is_null($value)) {
                        $fail($attribute.' is required for fixed target damage types');
                    } elseif ($value < 1 || $value > 10) {
                        $fail($attribute.' must be between 1 and 10');
                    }
                }
            },
        ]);

        return AttackSource::build(
            $request->name,
            $request->attackerPosition,
            $request->targetPosition,
            $request->targetPriority,
            $request->damageType,
            $request->tier,
            $request->targetsCount
        );
    }
}
