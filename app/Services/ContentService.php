<?php


namespace App\Services;


use App\Admin\Content\Sources\AttackSource;
use Illuminate\Support\Facades\Date;

class ContentService
{
    protected function getAttackDataFromJSON()
    {
        return json_decode(file_get_contents(resource_path('json/content/attacks.json')), true);
    }

    public function attacks()
    {
        $dataArray = $this->getAttackDataFromJSON();

        return collect($dataArray['data'])->map(function ($attackData) {

            return new AttackSource(
                $attackData['uuid'],
                $attackData['name'],
                $attackData['attacker_position_id'],
                $attackData['target_position_id'],
                $attackData['target_priority_id'],
                $attackData['damage_type_id'],
                $attackData['tier'],
                $attackData['targets_count'],
            );
        });
    }

    public function attacksLastUpdated()
    {
        $dataArray = $this->getAttackDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }
}
