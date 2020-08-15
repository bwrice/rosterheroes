@extends('admin.content.index-layout')

@section('index')

    @foreach($chestBlueprints as $chestBlueprint)
        <?php /** @var \App\Admin\Content\Sources\ChestBlueprintSource $chestBlueprint */ ?>
        <div class="col-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Description: {{$chestBlueprint->getDescription()}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Quality: {{$chestBlueprint->getQuality()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Size: {{$chestBlueprint->getSize()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Min Gold: {{$chestBlueprint->getMinGold()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Max Gold: {{$chestBlueprint->getMaxGold()}}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Item Blueprints:</h6>
                    <ul class="list-group mb-4">
                        @foreach($chestBlueprint->getItemBlueprints() as $itemBlueprintArray)
                            <?php
                            /** @var \Illuminate\Database\Eloquent\Collection $itemBlueprints */
                            /** @var \App\Admin\Content\Sources\ItemBlueprintSource $itemBlueprint */
                            /** @var array $itemBlueprintArray */
                            $itemBlueprint = $itemBlueprints->first(function (\App\Admin\Content\Sources\ItemBlueprintSource $itemBlueprintSource) use ($itemBlueprintArray) {
                                return $itemBlueprintSource->getUuid() === $itemBlueprintArray['uuid'];
                            });
                            ?>
                            <li class="list-group-item">{{$itemBlueprint->getDescription()}}</li>
                        @endforeach
                    </ul>
                    <a href="/admin/content/chest-blueprints/{{$chestBlueprint->getUuid()}}/edit" class="btn btn-block btn-outline-primary">Edit</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
