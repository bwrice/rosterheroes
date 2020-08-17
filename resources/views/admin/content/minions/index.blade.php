<?php
/**
 * @var \Illuminate\Database\Eloquent\Collection $enemyTypes
 * @var \Illuminate\Database\Eloquent\Collection $combatPositions
 * @var \Illuminate\Support\Collection $attacks
 */
?>
@extends('admin.content.index-layout')

@section('index')

    @foreach($minions as $minion)
        <?php /** @var \App\Admin\Content\Sources\MinionSource $minion */ ?>
        <div class="col-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$minion->getName()}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Level: {{$minion->getLevel()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Enemy Type: {{$enemyTypes->firstWhere('id', '=', $minion->getEnemyTypeID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Combat Position: {{$combatPositions->firstWhere('id', '=', $minion->getCombatPositionID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Attacks:</h6>
                    <ul class="list-group mb-4">
                        @foreach($minion->getAttacks() as $attackUuid)
                            <?php
                            /** @var \App\Admin\Content\Sources\AttackSource $attack */
                            /** @var string $attackUuid */
                            $attack = $attacks->first(function (\App\Admin\Content\Sources\AttackSource $attackSource) use ($attackUuid) {
                                return $attackSource->getUuid() === $attackUuid;
                            });
                            ?>
                            <li class="list-group-item">{{$attack->getName()}}</li>
                        @endforeach
                    </ul>
                    <a href="/admin/content/minions/{{$minion->getUuid()}}/edit" class="btn btn-block btn-outline-primary">Edit</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
