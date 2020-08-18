<?php


namespace App\Services;


class NPCService
{
    public function squadNames()
    {
        $squadArrays = collect(config($this->getBaseConfigKey() . '.squads'));
    }

    protected function getBaseConfigKey()
    {
        if (app()->environment('production')) {
            return 'npc.production';
        } elseif (app()->environment('beta')) {
            return 'npc.local';
        }
        return 'npc.development';
    }


}
