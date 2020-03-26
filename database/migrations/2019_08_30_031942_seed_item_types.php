<?php

use App\Domain\Models\ItemBase;
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
        $materialNames = [
            'Mammoth Tusk',
            'Sabertooth',
            'Minotaur Horn',
            'Spider Fang',
            'Dragon Bone',
            'Cotton',
            'Linen',
            'Wool',
            'Satin',
            'Velvet',
            'Silk',
            'Vicuna',
            'Ethereal',
            'Amber',
            'Ivory',
            'Jade',
            'Onyx',
            'Emerald',
            'Ruby',
            'Sapphire',
            'Diamond',
            'Wolf Pelt',
            'Leather',
            'Mammoth Hide',
            'Studded Leather',
            'Werewolf Pelt',
            'Scarab Shell',
            'Scorpion Shell',
            'Dragon Scale',
            'Copper',
            'Iron',
            'Bronze',
            'Steel',
            'Orichalcum',
            'Tungsten',
            'Elven',
            'Empyrean',
            'Silver',
            'Gold',
            'Platinum',
            'Acid',
            'Ice',
            'Fire',
            'Lightning',
            'Spirit',
            'Nova',
            'Yew',
            'Juniper',
            'Makore',
            'Mansonia',
            'Cebil',
            'Muninga',
            'Rosewood',
            'Elderwood',
            'Entwood'
        ];

        // TODO: limit materials for each type
        $itemBases = ItemBase::all();
        $materials = \App\Domain\Models\Material::all();
        $attacks = \App\Domain\Models\Attack::all();
        $itemTypes = [

            /*
             * WEAPONS
             */

            [
                'base' => ItemBase::DAGGER,
                'name' => 'Knife',
                'tier' => 1,
                'materials' => [
                ],
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Kris',
                'tier' => 2,
                'materials' => [
                ],
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Dirk',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Katar',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Stiletto',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Short Sword',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Falchion',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Scimitar',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Sabre',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Rapier',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SWORD,
                'name' => 'Ninjato',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Hatchet',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Hand Axe',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Ono',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Broad Axe',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Sagaris',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::AXE,
                'name' => 'Battle Axe',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Club',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Cudgel',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Flail',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Battle Mace',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Morning Star',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'War Hammer',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Straight Bow',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Longbow',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Recurve Bow',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Reflex Bow',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Composite Bow',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOW,
                'name' => 'Compound Bow',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Light Crossbow',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Heavy Crossbow',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Arbalest',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Repeating Crossbow',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Heavy Arbalest',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROSSBOW,
                'name' => 'Giant\'s Crossbow',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Darts',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Knives',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Stars',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Javelins',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Throwing Axes',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::THROWING_WEAPON,
                'name' => 'Exploding Flasks',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Spear',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Glaive',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Trident',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'War Scythe',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Naginata',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::POLEARM,
                'name' => 'Halberd',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Longsword',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Bastard Sword',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Claymore',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Greatsword',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Katana',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_SWORD,
                'name' => 'Giant\'s Sword',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Sprig',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Lesser Wand',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Scepter',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Greater Wand',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Greater Scepter',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::WAND,
                'name' => 'Magus Scepter',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Lesser Staff',
                'tier' => 1,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Rod',
                'tier' => 2,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Greater Staff',
                'tier' => 3,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Master\'s Rod',
                'tier' => 4,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Battle Staff',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::STAFF,
                'name' => 'Magus Staff',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Lesser Orb',
                'tier' => 20,
                'materials' => [
                ]
            ],
            [

                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Bardiche',
                'tier' => 21,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Spell Blade',
                'tier' => 30,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Spell Cleaver',
                'tier' => 32,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Lance',
                'tier' => 41,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Greater Orb',
                'tier' => 50,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Mind Blade',
                'tier' => 62,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Great Axe',
                'tier' => 64,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Mind Cleaver',
                'tier' => 65,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::MACE,
                'name' => 'Morning Star',
                'tier' => 5,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::DAGGER,
                'name' => 'Gladius',
                'tier' => 6,
                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Labrys',
                'tier' => 81,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_ONE_HAND,
                'name' => 'Conjure Blade',
                'tier' => 88,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ORB,
                'name' => 'Magus Orb',
                'tier' => 89,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_TWO_HAND,
                'name' => 'Conjure Cleaver',
                'tier' => 90,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::TWO_HAND_AXE,
                'name' => 'Giant\'s Axe',
                'tier' => 97,

                'materials' => [
                ]
            ],

            /*
             * ARMOR
             */

            [
                'base' => ItemBase::CAP,
                'name' => 'Fez',
                'tier' => 6,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Buckler',
                'tier' => 8,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Kettle Hat',
                'tier' => 9,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Frock',
                'tier' => 10,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Slippers',
                'tier' => 11,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Light Cuirass',
                'tier' => 12,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Light Boots',
                'tier' => 13,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Light Gloves',
                'tier' => 14,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Breastplate',
                'tier' => 15,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Beret',
                'tier' => 16,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Targe',
                'tier' => 17,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Light Gauntlets',
                'tier' => 18,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Skullcap',
                'tier' => 19,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Greaves',
                'tier' => 22,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Light Belt',
                'tier' => 23,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Pupil\'s Sash',
                'tier' => 25,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Spangenhelm',
                'tier' => 27,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Heavy Buckler',
                'tier' => 29,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Gages',
                'tier' => 31,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Coat',
                'tier' => 32,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Busby',
                'tier' => 33,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Heavy Boots',
                'tier' => 34,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Helm',
                'tier' => 35,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Barrier',
                'tier' => 37,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Clogs',
                'tier' => 38,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Heavy Gauntlets',
                'tier' => 39,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Plackart',
                'tier' => 40,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Rondache',
                'tier' => 42,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Chainmail',
                'tier' => 43,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Tassets',
                'tier' => 44,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Helm',
                'tier' => 45,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Trilby',
                'tier' => 48,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Heavy Belt',
                'tier' => 50,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Veteran\'s Sash',
                'tier' => 51,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Gages',
                'tier' => 52,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Bascinet',
                'tier' => 53,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Heater Shield',
                'tier' => 55,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Heavy Cuirass',
                'tier' => 56,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Scalemail',
                'tier' => 57,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Pantofles',
                'tier' => 58,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Mantle',
                'tier' => 59,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Tubetieka',
                'tier' => 60,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Combat Boots',
                'tier' => 61,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Armet',
                'tier' => 62,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Force-field',
                'tier' => 64,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Kite Shield',
                'tier' => 66,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'Battle Gauntlets',
                'tier' => 68,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Sallet',
                'tier' => 71,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Cuisses',
                'tier' => 72,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Wizard Hands',
                'tier' => 73,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Captain\'s Belt',
                'tier' => 74,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Master\'s Sash',
                'tier' => 75,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Wizard\'s Cap',
                'tier' => 76,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Cape',
                'tier' => 77,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Gothic Shield',
                'tier' => 78,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Hauberk',
                'tier' => 79,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Great Bascinet',
                'tier' => 80,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Platemail',
                'tier' => 82,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Tower Shield',
                'tier' => 85,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LEGGINGS,
                'name' => 'Chausses',
                'tier' => 87,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Fluted Armet',
                'tier' => 88,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHOES,
                'name' => 'Magus Pantofles',
                'tier' => 90,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GLOVES,
                'name' => 'Sorcerer Palms',
                'tier' => 91,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SASH,
                'name' => 'Magus Sash',
                'tier' => 92,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::GAUNTLETS,
                'name' => 'War Gauntlets',
                'tier' => 92,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::PSIONIC_SHIELD,
                'name' => 'Citadel',
                'tier' => 93,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BOOTS,
                'name' => 'Spartan Sandals',
                'tier' => 93,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CAP,
                'name' => 'Sorcerer\'s Hat',
                'tier' => 94,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::ROBES,
                'name' => 'Cloak',
                'tier' => 94,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::LIGHT_ARMOR,
                'name' => 'Ranger\'s Suit',
                'tier' => 94,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BELT,
                'name' => 'Giant\'s Belt',
                'tier' => 95,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HELMET,
                'name' => 'Sallet with Bevor',
                'tier' => 95,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::HEAVY_ARMOR,
                'name' => 'Phalanx Suit',
                'tier' => 96,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::SHIELD,
                'name' => 'Giant\'s Shield',
                'tier' => 97,

                'materials' => [
                ]
            ],

            /*
             * JEWELRY
             */

            [
                'base' => ItemBase::RING,
                'name' => 'Band',
                'tier' => 10,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Shackle',
                'tier' => 10,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Beeds',
                'tier' => 10,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Headband',
                'tier' => 28,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Ring',
                'tier' => 30,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Bracelet',
                'tier' => 30,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Necklace',
                'tier' => 30,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Circlet',
                'tier' => 36,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Chaplet',
                'tier' => 46,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Heavy Band',
                'tier' => 50,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Arm Band',
                'tier' => 50,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Locket',
                'tier' => 50,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Tiara',
                'tier' => 54,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Coronet',
                'tier' => 69,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Signet',
                'tier' => 70,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Bangle',
                'tier' => 70,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Pendant',
                'tier' => 70,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'Queen\'s Crown',
                'tier' => 81,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::RING,
                'name' => 'Royal Signet',
                'tier' => 90,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::BRACELET,
                'name' => 'Armlet',
                'tier' => 90,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::NECKLACE,
                'name' => 'Amulet',
                'tier' => 90,

                'materials' => [
                ]
            ],
            [
                'base' => ItemBase::CROWN,
                'name' => 'King\'s Crown',
                'tier' => 92,

                'materials' => [
                ]
            ],
        ];

        foreach ($itemTypes as $itemType) {
            /** @var \App\Domain\Models\ItemType $itemTypeModel */
            $itemTypeModel =  \App\Domain\Models\ItemType::query()->create([
                'name' => $itemType['name'],
                'item_base_id' => $itemBases->where('name', $itemType['base'])->first()->id,
                'tier' => $itemType['tier']
            ]);

            /** @var \Illuminate\Database\Eloquent\Collection $materials */
            $materialTypesToSave = $materials->whereIn('name', $itemType['materials']);

            $itemTypeModel->materials()->saveMany($materialTypesToSave);

            if (array_key_exists('attacks', $itemType)) {
                $attackToSave = $attacks->whereIn('name', $itemType['attacks']);
                $itemTypeModel->attacks()->saveMany($attackToSave);
            }
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
