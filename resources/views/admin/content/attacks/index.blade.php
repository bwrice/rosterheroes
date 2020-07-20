@extends('admin.content.index-layout')

@section('index')

    @foreach($attacks as $attack)
        <?php /** @var \App\Admin\Content\Sources\AttackSource $attack */ ?>
        <div class="col-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$attack->getName()}} ({{$attack->getTier()}})</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Attacker Position: {{$combatPositions->find($attack->getAttackerPositionID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Target Position: {{$combatPositions->find($attack->getTargetPositionID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Target Priority: {{$targetPriorities->find($attack->getTargetPriorityID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Damage Type: {{$damageTypes->find($attack->getDamageTypeID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Targets Count: {{$attack->getTargetsCount() ?: 'N/A'}}</h6>
                    <a href="/admin/content/attacks/{{$attack->getUuid()}}/edit" class="btn btn-block btn-outline-primary">Edit</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
