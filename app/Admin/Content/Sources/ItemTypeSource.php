<?php


namespace App\Admin\Content\Sources;


use App\Domain\Models\Attack;
use App\Domain\Models\ItemType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class ItemTypeSource implements Arrayable, Jsonable
{

    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $tier;
    /**
     * @var int
     */
    protected $itemBaseID;
    /**
     * @var array
     */
    protected $attackUuids;

    public function __construct(
        string $uuid,
        string $name,
        int $tier,
        int $itemBaseID,
        array $attackUuids)
    {

        $this->uuid = $uuid;
        $this->name = $name;
        $this->tier = $tier;
        $this->itemBaseID = $itemBaseID;
        $this->attackUuids = $attackUuids;
    }

    public static function build(string $name, int $tier, int $itemBaseID, array $attackUuids)
    {
        return new static(
            Str::uuid(),
            $name,
            $tier,
            $itemBaseID,
            $attackUuids
        );
    }

    public function update(ItemTypeSource $itemTypeSource)
    {
        $this->name = $itemTypeSource->name;
        $this->tier = $itemTypeSource->tier;
        $this->itemBaseID = $itemTypeSource->itemBaseID;
        $this->attackUuids = $itemTypeSource->attackUuids;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTier(): int
    {
        return $this->tier;
    }
    /**
     * @return int
     */
    public function getItemBaseID(): int
    {
        return $this->itemBaseID;
    }

    /**
     * @return array
     */
    public function getAttackUuids(): array
    {
        return $this->attackUuids;
    }

    /**
     * @param string $name
     * @return ItemTypeSource
     */
    public function setName(string $name): ItemTypeSource
    {
        $this->name = $name;
        return $this;
    }

    public function isSynced(ItemType $itemType)
    {
        if ($itemType->name !== $this->getName()) {
            return false;
        }
        if ($itemType->tier !== $this->getTier()) {
            return false;
        }
        if ($itemType->item_base_id !== $this->getItemBaseID()) {
            return false;
        }
        $attackUuids = $itemType->attacks->map(function (Attack $attack) {
            return $attack->uuid;
        })->toArray();
        if ($attackUuids !== $this->getAttackUuids()) {
            return false;
        }
        return true;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'tier' => $this->getTier(),
            'item_base_id' => $this->getItemBaseID(),
            'attackUuids' => $this->getAttackUuids()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param string $uuid
     * @return ItemTypeSource
     */
    public function setUuid(string $uuid): ItemTypeSource
    {
        $this->uuid = $uuid;
        return $this;
    }
}
