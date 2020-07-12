@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row m-4">
            <div class="col-12">
                <h1 class="display-4 text-center my-6">Create a New Attack</h1>
                @foreach($errors->all() as $message)
                    <div class="alert alert-danger">{{$message}}</div>
                @endforeach
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    <div class="alert alert-success">{{ \Illuminate\Support\Facades\Session::get('success') }}</div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-8 offset-2">
                <form method="post" action="/admin/content/attacks">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Attack Name</label>
                                <input class="form-control" name="name" id="name" aria-describedby="name" required>
                            </div>
                            <div class="form-group">
                                <label for="tier">Tier</label>
                                <input type="number" name="tier" class="form-control" id="tier" aria-describedby="tier"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="targetsCount">Targets Count</label>
                                <input type="number" name="targetsCount" class="form-control" id="targetsCount" aria-describedby="targetsCount">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="attackerPosition">Attacker Position</label>
                                <select class="form-control" name="attackerPosition" id="attackerPosition">
                                    @foreach($combatPositions as $combatPosition)
                                        <?php /** @var \App\Domain\Models\CombatPosition $combatPosition */ ?>
                                        <option value="{{$combatPosition->id}}">{{$combatPosition->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="targetPosition">Target Position</label>
                                <select class="form-control" name="targetPosition" id="targetPosition">
                                    @foreach($combatPositions as $combatPosition)
                                        <?php /** @var \App\Domain\Models\CombatPosition $combatPosition */ ?>
                                        <option value="{{$combatPosition->id}}">{{$combatPosition->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="targetPriority">Target Priority</label>
                                <select class="form-control" name="targetPriority" id="targetPriority">
                                    @foreach($targetPriorities as $targetPriority)
                                        <?php /** @var \App\Domain\Models\TargetPriority $targetPriority */ ?>
                                        <option value="{{$targetPriority->id}}">{{$targetPriority->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="damageType">Damage Type</label>
                                <select class="form-control" name="damageType" id="damageType">
                                    @foreach($damageTypes as $damageType)
                                        <?php /** @var \App\Domain\Models\DamageType $damageType */ ?>
                                        <option value="{{$damageType->id}}">{{$damageType->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
