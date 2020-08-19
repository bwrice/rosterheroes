<?php


namespace App\Services;


use App\Domain\Models\Squad;
use App\Domain\Models\User;

class NPCService
{
    public function squadName()
    {
        $squads = $this->getSquads()->random();
        return $squads['name'];
    }

    public function user()
    {
        return User::query()->where('email', '=', config('npc.user.email'))->firstOrFail();
    }

    protected function getBaseConfigKey()
    {
        return 'npc.' . app()->environment();
    }

    protected function getSquads()
    {
        $key = $this->getBaseConfigKey() . '.squads';
        return collect(config($key));
    }

    public function isNPC(Squad $squad)
    {
        return $squad->user_id === $this->user()->id;
    }
}
