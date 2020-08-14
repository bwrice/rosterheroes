<?php

namespace App\Http\Controllers;

use App\Admin\Content\ViewModels\AttackContentViewModel;
use App\Admin\Content\ViewModels\ChestBlueprintContentViewModel;
use App\Admin\Content\ViewModels\ItemBlueprintContentViewModel;
use App\Admin\Content\ViewModels\ItemTypeContentViewModel;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show()
    {
        return view('admin.content.main', [
            'contentViewModels' => collect([
                new AttackContentViewModel(),
                new ItemTypeContentViewModel(),
                new ItemBlueprintContentViewModel(),
                new ChestBlueprintContentViewModel()
            ])
        ]);
    }
}
