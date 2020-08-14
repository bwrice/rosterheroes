@extends('admin.content.index-layout')

@section('index')

    @foreach($itemBlueprints as $itemBlueprint)
        <?php /** @var \App\Admin\Content\Sources\ItemBlueprintSource $itemBlueprint */ ?>
        <div class="col-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Description: {{$itemBlueprint->getDescription()}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Item Name: {{$itemBlueprint->getItemName()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Enchantment Power: {{$itemBlueprint->getEnchantmentPower()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Item Bases:</h6>
                    <ul class="list-group mb-4">
                        @foreach($itemBlueprint->getItemBases() as $itemBaseID)
                            <?php
                            /** @var \Illuminate\Database\Eloquent\Collection $itemBases */
                            /** @var \App\Domain\Models\ItemBase $itemBase */
                            /** @var int $itemBaseID */
                            $itemBase = $itemBases->first(function (\App\Domain\Models\ItemBase $itemBase) use ($itemBaseID) {
                                return $itemBase->id === $itemBaseID;
                            });
                            ?>
                            <li class="list-group-item">{{$itemBase->name}}</li>
                        @endforeach
                    </ul>
                    <h6 class="card-subtitle mb-2 text-muted">Item Classes:</h6>
                    <ul class="list-group mb-4">
                        @foreach($itemBlueprint->getItemClasses() as $itemClassID)
                            <?php
                            /** @var \Illuminate\Database\Eloquent\Collection $itemClasses */
                            /** @var \App\Domain\Models\ItemClass $itemClass */
                            /** @var int $itemClassID */
                            $itemClass = $itemClasses->first(function (\App\Domain\Models\ItemClass $itemClass) use ($itemClassID) {
                                return $itemClass->id === $itemClassID;
                            });
                            ?>
                            <li class="list-group-item">{{$itemClass->name}}</li>
                        @endforeach
                    </ul>
                    <h6 class="card-subtitle mb-2 text-muted">Item Types:</h6>
                    <ul class="list-group mb-4">
                        @foreach($itemBlueprint->getItemTypes() as $itemTypeUuid)
                            <?php
                            /** @var \Illuminate\Database\Eloquent\Collection $itemTypes */
                            /** @var \App\Domain\Models\ItemType $itemType */
                            /** @var int $itemTypeID */
                            $itemType = $itemTypes->first(function (\App\Domain\Models\ItemType $itemType) use ($itemTypeID) {
                                return $itemType->id === $itemTypeID;
                            });
                            ?>
                            <li class="list-group-item">{{$itemType->name}}</li>
                        @endforeach
                    </ul>
                    <a href="/admin/content/item-blueprints/{{$itemBlueprint->getUuid()}}/edit" class="btn btn-block btn-outline-primary">Edit</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
