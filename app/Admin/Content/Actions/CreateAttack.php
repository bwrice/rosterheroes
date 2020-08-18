<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\AttackSource;
use App\Facades\Content;

class CreateAttack
{
    public function execute(AttackSource $attackSource)
    {
        $attacks = Content::attacks();
        $attacks->push($attackSource);

        $lastUpdated = now()->timestamp;

        file_put_contents(Content::attacksPath(), json_encode([
            'last_updated' => $lastUpdated,
            'data' => $attacks->toArray()
        ], JSON_PRETTY_PRINT));
    }
}
