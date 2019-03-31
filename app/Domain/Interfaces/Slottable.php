<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/8/18
 * Time: 9:38 PM
 */

namespace App\Domain\Interfaces;


use App\Domain\Collections\SlotCollection;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

/**
 * Interface Slottable
 * @package App\Slots
 *
 * @property int|null $slot_type_id
 * @property string|null
 */
interface Slottable
{
    public function getSlotTypeIDs(): array;

    public function getSlotsCount(): int;

    /**
     * @return HasOneOrMany
     */
    public function slots();

    public function getSlots(): SlotCollection;
}