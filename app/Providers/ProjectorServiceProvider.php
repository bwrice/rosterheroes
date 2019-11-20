<?php

namespace App\Providers;

use App\Projectors\ItemBlueprintProjector;
use Illuminate\Support\ServiceProvider;
use Spatie\EventSourcing\Projectionist;

class ProjectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @param Projectionist $projectionist
     *
     * @return void
     */
    public function boot(Projectionist $projectionist)
    {
//        $projectionist->addProjector(ItemBlueprintProjector::class);
    }
}
