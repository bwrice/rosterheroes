<?php


namespace App\Domain\Behaviors\Realm;


class RealmBehavior
{
    /**
     * @var string
     */
    private $realmColor;
    /**
     * @var array
     */
    private $realmViewBox;

    public function __construct(string $realmColor, array $realmViewBox)
    {
        $this->realmColor = $realmColor;
        $this->realmViewBox = $realmViewBox;
    }

    /**
     * @return string
     */
    public function getRealmColor(): string
    {
        return $this->realmColor;
    }

    /**
     * @return array
     */
    public function getRealmViewBox(): array
    {
        return $this->realmViewBox;
    }
}
