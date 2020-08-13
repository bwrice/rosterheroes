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
        $matchingKey = $attacks->search(function (AttackSource $originalAttackSource) use ($attackSource) {
            return (string) $originalAttackSource->getUuid() === (string) $attackSource->getUuid();
        });

        if ($matchingKey === false) {
            throw new \Exception("Attack with uuid: " . $attackSource->getUuid() . " not found in sources");
        }

        $updatedAttacks = $attacks->replace([$matchingKey => $attackSource]);
        $lastUpdated = now()->timestamp;

        file_put_contents(Content::attacksPath(), json_encode([
            'last_updated' => $lastUpdated,
            'data' => $updatedAttacks->toArray()
        ], JSON_PRETTY_PRINT));
    }
}
