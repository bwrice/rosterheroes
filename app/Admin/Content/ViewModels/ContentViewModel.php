<?php


namespace App\Admin\Content\ViewModels;


use Carbon\CarbonInterface;

interface ContentViewModel
{
    public function getTitle(): string;

    public function totalCount(): int;

    public function outOfSynCount(): int;

    public function lastUpdated(): CarbonInterface;

    public function createURL(): string;

    public function syncActionURL(): string;
}
