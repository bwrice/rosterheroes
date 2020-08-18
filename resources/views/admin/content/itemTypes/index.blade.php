@extends('admin.content.index-layout')

@section('index')

    @foreach($itemTypes as $itemType)
        <?php /** @var \App\Admin\Content\Sources\ItemTypeSource $itemType */ ?>
        <div class="col-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$itemType->getName()}} ({{$itemType->getTier()}})</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Item Base: {{$itemBases->find($itemType->getItemBaseID())->name}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Attacks:</h6>
                    <ul class="list-group">
                        @foreach($itemType->getAttackUuids() as $attackUuid)
                            <?php
                            /** @var \Illuminate\Database\Eloquent\Collection $attacks */
                            /** @var string $attackUuid */
                            /** @var \App\Admin\Content\Sources\AttackSource $attackSource */
                            $attackSource = $attacks->first(function (\App\Admin\Content\Sources\AttackSource $attack) use ($attackUuid) {
                                return (string) $attack->getUuid() === $attackUuid;
                            });
                            ?>
                            <li class="list-group-item">{{$attackSource->getName()}}</li>
                        @endforeach
                    </ul>
                    <a href="/admin/content/item-types/{{$itemType->getUuid()}}/edit" class="btn btn-block btn-outline-primary">Edit</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
