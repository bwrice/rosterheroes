@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row my-4">
            <div class="col-12">
                <h1 class="text-center">Attacks</h1>
            </div>
        </div>
        <div class="row">
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
            <div class="col-4 offset-4">
                <ul class="pagination">
                    @if($page > 3)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page-3}}">{{$page - 3}}</a></li>
                    @endif
                    @if($page > 2)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page-2}}">{{$page - 2}}</a></li>
                    @endif
                    @if($page > 1)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page-1}}">{{$page - 1}}</a></li>
                    @endif
                        <li class="page-item disabled active"><a class="page-link" href="#">{{$page}}</a></li>
                    @if($page < $totalPages)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page+1}}">{{$page + 1}}</a></li>
                    @endif
                    @if($page < $totalPages - 1)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page+2}}">{{$page + 2}}</a></li>
                    @endif
                    @if($page < $totalPages - 2)
                        <li class="page-item"><a class="page-link" href="/admin/content/attacks?page={{$page+3}}">{{$page + 3}}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

@endsection
