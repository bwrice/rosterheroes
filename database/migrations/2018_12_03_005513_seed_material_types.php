<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedMaterialTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $materialGroups = \App\Domain\Models\MaterialGroup::all();

        $materials = [
            [
                'name' => 'Wolf Pelt',
                'grade' => 2,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Copper',
                'grade' => 3,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Cotton',
                'grade' => 4,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Yew',
                'grade' => 6,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Iron',
                'grade' => 8,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Amber',
                'grade' => 9,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Linen',
                'grade' => 11,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Leather',
                'grade' => 12,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Juniper',
                'grade' => 15,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Ivory',
                'grade' => 17,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Wool',
                'grade' => 20,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Mammoth Hide',
                'grade' => 23,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Mammoth Tusk',
                'grade' => 27,
                'material_group' => \App\Domain\Models\MaterialGroup::BONE
            ],
            [
                'name' => 'Bronze',
                'grade' => 29,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Acid',
                'grade' => 30,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ],
            [
                'name' => 'Studded Leather',
                'grade' => 32,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Makore',
                'grade' => 34,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Satin',
                'grade' => 35,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Steel',
                'grade' => 36,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Jade',
                'grade' => 38,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Werewolf Pelt',
                'grade' => 40,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Ice',
                'grade' => 41,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ],
            [
                'name' => 'Sabertooth',
                'grade' => 42,
                'material_group' => \App\Domain\Models\MaterialGroup::BONE
            ],
            [
                'name' => 'Silver',
                'grade' => 43,
                'material_group' => \App\Domain\Models\MaterialGroup::PRECIOUS_METAL
            ],
            [
                'name' => 'Mansonia',
                'grade' => 45,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Fire',
                'grade' => 47,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ],
            [
                'name' => 'Velvet',
                'grade' => 49,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Onyx',
                'grade' => 50,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Orichalcum',
                'grade' => 51,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Minotaur Horn',
                'grade' => 53,
                'material_group' => \App\Domain\Models\MaterialGroup::BONE
            ],
            [
                'name' => 'Scarab Shell',
                'grade' => 54,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Cebil',
                'grade' => 56,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Emerald',
                'grade' => 58,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Gold',
                'grade' => 61,
                'material_group' => \App\Domain\Models\MaterialGroup::PRECIOUS_METAL
            ],
            [
                'name' => 'Spider Fang',
                'grade' => 63,
                'material_group' => \App\Domain\Models\MaterialGroup::BONE
            ],
            [
                'name' => 'Lightning',
                'grade' => 64,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ],
            [
                'name' => 'Muninga',
                'grade' => 65,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Silk',
                'grade' => 67,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Ruby',
                'grade' => 70,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Scorpion Shell',
                'grade' => 71,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Rosewood',
                'grade' => 73,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Tungsten',
                'grade' => 76,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Sapphire',
                'grade' => 79,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Vicuna',
                'grade' => 82,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Platinum',
                'grade' => 83,
                'material_group' => \App\Domain\Models\MaterialGroup::PRECIOUS_METAL
            ],
            [
                'name' => 'Elderwood',
                'grade' => 84,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Spirit',
                'grade' => 85,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ],
            [
                'name' => 'Elven',
                'grade' => 87,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Diamond',
                'grade' => 88,
                'material_group' => \App\Domain\Models\MaterialGroup::GEMSTONE
            ],
            [
                'name' => 'Entwood',
                'grade' => 90,
                'material_group' => \App\Domain\Models\MaterialGroup::WOOD
            ],
            [
                'name' => 'Ethereal',
                'grade' => 92,
                'material_group' => \App\Domain\Models\MaterialGroup::CLOTH
            ],
            [
                'name' => 'Dragon Bone',
                'grade' => 94,
                'material_group' => \App\Domain\Models\MaterialGroup::BONE
            ],
            [
                'name' => 'Dragon Scale',
                'grade' => 95,
                'material_group' => \App\Domain\Models\MaterialGroup::HIDE
            ],
            [
                'name' => 'Empyrean',
                'grade' => 97,
                'material_group' => \App\Domain\Models\MaterialGroup::METAL
            ],
            [
                'name' => 'Nova',
                'grade' => 98,
                'material_group' => \App\Domain\Models\MaterialGroup::PSIONIC
            ]
        ];

        foreach ($materials as $material) {
            \App\Domain\Models\MaterialType::create([
                'name' => $material['name'],
                'grade' => $material['grade'],
                'material_group_id' => $materialGroups->where('name', '=', $material['material_group'])->first()->id
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
        \App\Domain\Models\MaterialType::query()->delete();
    }
}
