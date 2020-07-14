<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\AttackSource;
use App\Facades\Content;

class UpdateAttack
{
    public function execute(AttackSource $attackSource)
    {
        $attacks = Content::attacks();
        /** @var AttackSource $match */
        $match = $attacks->first(function (AttackSource $originalAttackSource) use ($attackSource) {
            return (string) $originalAttackSource->getUuid() === (string) $attackSource->getUuid();
        });

        if (! $match) {
            throw new \Exception("Attack with uuid: " . $attackSource->getUuid() . " not found in sources");
        }

        $match->update($attackSource);
        $lastUpdated = now()->timestamp;

        file_put_contents(Content::attacksPath(), json_encode([
            'last_updated' => $lastUpdated,
            'data' => $attacks->toArray()
        ], JSON_PRETTY_PRINT));
    }
}
