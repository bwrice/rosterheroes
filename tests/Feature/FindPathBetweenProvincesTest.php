<?php

namespace Tests\Feature;

use App\Domain\Actions\FindPathBetweenProvinces;
use App\Domain\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindPathBetweenProvincesTest extends TestCase
{
    /**
     * @return FindPathBetweenProvinces
     */
    protected function getDomainAction()
    {
        return app(FindPathBetweenProvinces::class);
    }

    /**
     * @param string $start
     * @param string $end
     * @param array $path
     * @dataProvider provides_it_will_return_the_expected_path_between_provinces
     * @test
     */
    public function it_will_return_the_expected_path_between_provinces(string $start, string $end, array $path)
    {
        /** @var Province $startProvince */
        $startProvince = Province::query()->where('name', '=', $start)->firstOrFail();
        /** @var Province $endProvince */
        $endProvince = Province::query()->where('name', '=', $end)->firstOrFail();

        $provinces = $this->getDomainAction()->execute($startProvince, $endProvince);

        $provinceNames = $provinces->map(function (Province $province) {
            return $province->name;
        })->toArray();

        $this->assertEquals($path, $provinceNames);
    }

    public function provides_it_will_return_the_expected_path_between_provinces()
    {
        return [
            'Ghrax to Hynarice' => [
                'start' => 'Ghrax',
                'end' => 'Hynarice',
                'path' => [
                    'Ghrax',
                    'Julifidyl',
                    'Nuzil',
                    'Hynarice'
                ]
            ],
            'Hynarice to Ghrax' => [
                'start' => 'Hynarice',
                'end' => 'Ghrax',
                'path' => [
                    'Hynarice',
                    'Nuzil',
                    'Julifidyl',
                    'Ghrax'
                ]
            ],
            'Uplemas to Nobulem' => [
                'start' => 'Uplemas',
                'end' => 'Nobulem',
                'path' => [
                    'Uplemas',
                    'Vyspen',
                    'Goshala',
                    'Trovubar',
                    'Skodal',
                    'Nobulem'
                ]
            ],
            'Lynaesk to Sasyneus' => [
                'start' => 'Lynaesk',
                'end' => 'Sasyneus',
                'path' => [
                    'Lynaesk',
                    'Ruashen',
                    'Euwharo',
                    'Xilydor',
                    'Sasyneus'
                ]
            ],
        ];
    }
}
