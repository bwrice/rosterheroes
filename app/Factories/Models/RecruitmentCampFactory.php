<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\RecruitmentCamp;
use Illuminate\Support\Str;

class RecruitmentCampFactory
{
    protected $provinceID;

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return RecruitmentCamp
     */
    public function create(array $extra = []): RecruitmentCamp
    {
        /** @var RecruitmentCamp $recruitmentCamp */
        $recruitmentCamp = RecruitmentCamp::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Shop ' . rand(1, 999999),
            'province_id' => $this->getProvinceID(),
        ], $extra));


        return $recruitmentCamp;
    }

    public function withProvinceID(int $provinceID)
    {
        $clone = clone $this;
        $clone->provinceID = $provinceID;
        return $clone;
    }

    protected function getProvinceID()
    {
        return $this->provinceID ?: Province::query()->inRandomOrder()->first()->id;
    }
}
