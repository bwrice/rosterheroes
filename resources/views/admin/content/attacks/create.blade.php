@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row m-4">
            <div class="col-12">
                <h1 class="display-4 text-center my-6">Create a New Attack</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-8 offset-2">
                <form>
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Attack Name</label>
                                <input class="form-control" id="name" aria-describedby="name" required>
                            </div>
                            <div class="form-group">
                                <label for="tier">Tier</label>
                                <input type="number" class="form-control" id="tier" aria-describedby="tier"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="targetsCount">Targets Count</label>
                                <input type="number" class="form-control" id="targetsCount" aria-describedby="targetsCount">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="attackerPosition">Attacker Position</label>
                                <select class="form-control" id="attackerPosition">
                                    @foreach($combatPositions as $combatPosition)
                                        <?php /** @var \App\Domain\Models\CombatPosition $combatPosition */ ?>
                                        <option value="{{$combatPosition->id}}">{{$combatPosition->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="targetPosition">Target Position</label>
                                <select class="form-control" id="targetPosition">
                                    @foreach($combatPositions as $combatPosition)
                                        <?php /** @var \App\Domain\Models\CombatPosition $combatPosition */ ?>
                                        <option value="{{$combatPosition->id}}">{{$combatPosition->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="targetPosition">Target Priority</label>
                                <select class="form-control" id="targetPosition">
                                    @foreach($targetPriorities as $targetPriority)
                                        <?php /** @var \App\Domain\Models\TargetPriority $targetPriority */ ?>
                                        <option value="{{$targetPriority->id}}">{{$targetPriority->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="targetPosition">Damage Type</label>
                                <select class="form-control" id="targetPosition">
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
