<?php

namespace App\Http\Controllers;

use App\Admin\Content\ViewModels\AttackContentViewModel;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show()
    {
        return view('admin.content.main', [
            'contentViewModels' => collect([
                AttackContentViewModel::build(),
                AttackContentViewModel::build(),
                AttackContentViewModel::build(),
                AttackContentViewModel::build(),
                AttackContentViewModel::build(),
                AttackContentViewModel::build()
            ])
        ]);
    }
}
