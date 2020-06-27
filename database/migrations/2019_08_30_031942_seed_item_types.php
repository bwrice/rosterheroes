<?php

use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use Illuminate\Database\Migrations\Migration;

class SeedItemTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $itemBases = ItemBase::all();
        $itemTypes = [

            /*
             * WEAPONS
             */

            [
                'base' => ItemBase::DAGGER,
                'name' => 'Knife',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Kris',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Dirk',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Katar',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Stiletto',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Gladius',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Short Sword',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Falchion',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Scimitar',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Sabre',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Rapier',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Ninjato',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Hatchet',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Hand Axe',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Ono',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Broad Axe',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Sagaris',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Battle Axe',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Club',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Cudgel',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Flail',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Battle Mace',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Morning Star',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'War Hammer',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Straight Bow',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Longbow',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Recurve Bow',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Reflex Bow',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Composite Bow',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Compound Bow',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Light Crossbow',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Heavy Crossbow',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Arbalest',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Repeating Crossbow',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Heavy Arbalest',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Giant\'s Crossbow',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Darts',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Knives',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Stars',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Javelins',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Axes',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Exploding Flasks',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Spear',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Glaive',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Trident',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'War Scythe',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Naginata',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Halberd',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Longsword',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Bastard Sword',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Claymore',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Greatsword',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Katana',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Giant\'s Sword',
                'tier' => 6,
            ],
            [

                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Long Axe',
                'tier' => 1,
            ],
            [

                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Bardiche',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Lance',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Great Axe',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Labrys',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Giant\'s Axe',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Sprig',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Lesser Wand',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Scepter',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Greater Wand',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Greater Scepter',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Magus Scepter',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Lesser Orb',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Polished Orb',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Greater Orb',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Seer\'s Sphere',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Magus Orb',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Wizard\'s Eye',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Lesser Staff',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Rod',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Greater Staff',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Master\'s Rod',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Battle Staff',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Magus Staff',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Spell Blade',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Mind Blade',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Conjure Blade',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Vision Blade',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Rune Blade',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Magus Blade',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Spell Cleaver',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Mind Cleaver',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Conjure Cleaver',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Vision Cleaver',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Rune Cleaver',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Magus Cleaver',
                'tier' => 6,
            ],

            /*
             * SHIELDS
             */

            [
                'base' => ItemBase::SHIELD,
                'name' => 'Buckler',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Rondache',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Heater Shield',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Kite Shield',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Gothic Shield',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Tower Shield',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Barrier',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Force-field',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Citadel',
                'tier' => 5,
            ],

            /*
             * CLOTHING
             */
            [
                'base' => ItemBase::CAP,
                'name' => 'Fez',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Beret',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Trilby',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Apprentice Cap',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Wizard\'s Cap',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Sorcerer\'s Hat',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Frock',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Coat',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Mantle',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Cape',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Cloak',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Magus Cloak',
                'tier' => 6,
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Slippers',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Clogs',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Pantofles',
                'tier' => 3,
            ],

            /*
             * ARMOR
             */
            [
                'base' => ItemBase::HELMET,
                'name' => 'Skullcap',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Kettle Hat',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Helm',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Bascinet',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Armet',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Sallet',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::BOOTS,
                'name' => 'Light Boots',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Heavy Boots',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Combat Boots',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Spartan Sandals',
                'tier' => 4,
            ],

            [
                'base' => ItemBase::GLOVES,
                'name' => 'Light Gloves',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Heavy Gloves',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Wizard Hands',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Sorcerer Palms',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Light Cuirass',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Plackart',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Heavy Cuirass',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Heavy Plackart',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Hauberk',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Ranger\'s Suit',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Light Gauntlets',
                'tier' => 1,

            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Heavy Gauntlets',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Battle Gauntlets',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'War Gauntlets',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Breastplate',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Chainmail',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Scalemail',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Platemail',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Heavy Hauberk',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Phalanx Suit',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::BELT,
                'name' => 'Light Belt',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Heavy Belt',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Captain\'s Belt',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Giant\'s Belt',
                'tier' => 6,
            ],

            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Greaves',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Tassets',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Cuisses',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Chausses',
                'tier' => 4,
            ],

            [
                'base' => ItemBase::SASH,
                'name' => 'Pupil\'s Sash',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Apprentice\'s Sash',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Scholar\'s Sash',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Veteran\'s Sash',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Master\'s Sash',
                'tier' => 5,

            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Magus Sash',
                'tier' => 6,
            ],

            /*
             * JEWELRY
             */

            [
                'base' => ItemBase::RING,
                'name' => 'Band',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Ring',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Heavy Band',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Signet',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Royal Signet',
                'tier' => 5,
            ],

            [
                'base' => ItemBase::BRACELET,
                'name' => 'Shackle',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Bracelet',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Arm Band',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Bangle',
                'tier' => 4,

            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Armlet',
                'tier' => 5,
            ],

            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Beads',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Necklace',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Locket',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Pendant',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Amulet',
                'tier' => 5,
            ],

            [
                'base' => ItemBase::CROWN,
                'name' => 'Headband',
                'tier' => 1,
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Circlet',
                'tier' => 2,
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Chaplet',
                'tier' => 3,
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Coronet',
                'tier' => 4,
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Queen\'s Crown',
                'tier' => 5,
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'King\'s Crown',
                'tier' => 6,
            ],
        ];

        foreach ($itemTypes as $itemType) {
            ItemType::query()->create([
                'name' => $itemType['name'],
                'item_base_id' => $itemBases->where('name', $itemType['base'])->first()->id,
                'tier' => $itemType['tier']
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
