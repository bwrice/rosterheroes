@extends('admin.admin')

@section('content')

    <?php
    /**
     * @var \App\Admin\Content\Sources\ItemTypeSource $itemTypeSource
     */
    ?>

    <div class="container">
        <div class="row m-4">
            <div class="col-12">
                <h1 class="display-4 text-center my-6">Edit Item Type</h1>
                @foreach($errors->all() as $message)
                    <div class="alert alert-danger">{{$message}}</div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-8 offset-2">
                <form method="post" action="/admin/content/item-types/{{$itemTypeSource->getUuid()}}">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" name="name" id="name" aria-describedby="name" value="{{$itemTypeSource->getName()}}" required>
                            </div>
                            <div class="form-group">
                                <label for="tier">Tier</label>
                                <input type="number" name="tier" class="form-control" id="tier" aria-describedby="tier" value="{{$itemTypeSource->getTier()}}"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="itemBase">Item Base</label>
                                <select class="form-control" name="itemBase" id="itemBase">
                                    @foreach($itemBases as $itemBase)
                                        <?php /** @var \App\Domain\Models\ItemBase $itemBase */ ?>
                                        <option value="{{$itemBase->id}}" {{$itemBase->id === $itemTypeSource->getItemBaseID() ? "selected" : ""}}>{{$itemBase->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="attacks">Attacks</label>
                                <select multiple class="form-control" name="attacks" id="attacks" style="height: 800px">
                                    @foreach($attackSources as $attackSource)
                                        <?php /** @var \App\Admin\Content\Sources\AttackSource $attackSource */ ?>
                                        <option value="{{$attackSource->getUuid()}}" {{in_array($attackSource->getUuid(), $itemTypeSource->getAttackUuids()) ? "selected" : ""}}>{{$attackSource->getName()}}</option>
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
