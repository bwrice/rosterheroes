<?php

namespace App\Http\Controllers;

use App\Admin\Content\Actions\SyncItemTypes;
use App\Facades\Content;
use Illuminate\Http\Request;

class SyncItemTypesController extends Controller
{
    public function __invoke(SyncItemTypes $syncItemTypes)
    {
        $synced = $syncItemTypes->execute();
        session()->flash('success', $synced->count() . ' item types synced');
        return redirect('/admin/content');
    }
}
