<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $materialTypes = \App\Domain\Models\MaterialType::all();

        $materials = [
            [
                'name' => 'Wolf Pelt',
                'grade' => 2,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Copper',
                'grade' => 3,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Cotton',
                'grade' => 4,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Yew',
                'grade' => 6,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Iron',
                'grade' => 8,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Amber',
                'grade' => 9,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Linen',
                'grade' => 11,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Leather',
                'grade' => 12,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Juniper',
                'grade' => 15,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Ivory',
                'grade' => 17,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Wool',
                'grade' => 20,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Mammoth Hide',
                'grade' => 23,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Mammoth Tusk',
                'grade' => 27,
                'material_group' => \App\Domain\Models\MaterialType::BONE
            ],
            [
                'name' => 'Bronze',
                'grade' => 29,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Acid',
                'grade' => 30,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ],
            [
                'name' => 'Studded Leather',
                'grade' => 32,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Makore',
                'grade' => 34,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Satin',
                'grade' => 35,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Steel',
                'grade' => 36,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Jade',
                'grade' => 38,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Werewolf Pelt',
                'grade' => 40,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Ice',
                'grade' => 41,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ],
            [
                'name' => 'Sabertooth',
                'grade' => 42,
                'material_group' => \App\Domain\Models\MaterialType::BONE
            ],
            [
                'name' => 'Silver',
                'grade' => 43,
                'material_group' => \App\Domain\Models\MaterialType::PRECIOUS_METAL
            ],
            [
                'name' => 'Mansonia',
                'grade' => 45,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Fire',
                'grade' => 47,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ],
            [
                'name' => 'Velvet',
                'grade' => 49,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Onyx',
                'grade' => 50,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Orichalcum',
                'grade' => 51,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Minotaur Horn',
                'grade' => 53,
                'material_group' => \App\Domain\Models\MaterialType::BONE
            ],
            [
                'name' => 'Scarab Shell',
                'grade' => 54,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Cebil',
                'grade' => 56,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Emerald',
                'grade' => 58,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Gold',
                'grade' => 61,
                'material_group' => \App\Domain\Models\MaterialType::PRECIOUS_METAL
            ],
            [
                'name' => 'Spider Fang',
                'grade' => 63,
                'material_group' => \App\Domain\Models\MaterialType::BONE
            ],
            [
                'name' => 'Lightning',
                'grade' => 64,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ],
            [
                'name' => 'Muninga',
                'grade' => 65,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Silk',
                'grade' => 67,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Ruby',
                'grade' => 70,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Scorpion Shell',
                'grade' => 71,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Rosewood',
                'grade' => 73,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Tungsten',
                'grade' => 76,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Sapphire',
                'grade' => 79,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Vicuna',
                'grade' => 82,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Platinum',
                'grade' => 83,
                'material_group' => \App\Domain\Models\MaterialType::PRECIOUS_METAL
            ],
            [
                'name' => 'Elderwood',
                'grade' => 84,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Spirit',
                'grade' => 85,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ],
            [
                'name' => 'Elven',
                'grade' => 87,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Diamond',
                'grade' => 88,
                'material_group' => \App\Domain\Models\MaterialType::GEMSTONE
            ],
            [
                'name' => 'Entwood',
                'grade' => 90,
                'material_group' => \App\Domain\Models\MaterialType::WOOD
            ],
            [
                'name' => 'Ethereal',
                'grade' => 92,
                'material_group' => \App\Domain\Models\MaterialType::CLOTH
            ],
            [
                'name' => 'Dragon Bone',
                'grade' => 94,
                'material_group' => \App\Domain\Models\MaterialType::BONE
            ],
            [
                'name' => 'Dragon Scale',
                'grade' => 95,
                'material_group' => \App\Domain\Models\MaterialType::HIDE
            ],
            [
                'name' => 'Empyrean',
                'grade' => 97,
                'material_group' => \App\Domain\Models\MaterialType::METAL
            ],
            [
                'name' => 'Nova',
                'grade' => 98,
                'material_group' => \App\Domain\Models\MaterialType::PSIONIC
            ]
        ];

        foreach ($materials as $material) {
            \App\Domain\Models\Material::query()->create([
                'name' => $material['name'],
                'grade' => $material['grade'],
                'material_type_id' => $materialTypes->where('name', '=', $material['material_group'])->first()->id
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
        \App\Domain\Models\Material::query()->delete();
    }
}
