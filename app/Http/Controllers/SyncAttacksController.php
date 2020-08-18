<?php

namespace App\Http\Controllers;

use App\Admin\Content\Actions\SyncAttacks;

class SyncAttacksController extends Controller
{
    public function __invoke(SyncAttacks $syncAttacks)
    {
        $updatedAttacks = $syncAttacks->execute();
        session()->flash('success', $updatedAttacks->count() . ' attacks synced');
        return redirect('/admin/content');
    }
}
