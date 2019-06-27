<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 9:48 PM
 */

namespace App\Domain\Interfaces;


use Illuminate\Database\Eloquent\Builder;

interface SalaryQueryable
{
    public function minSalary(int $amount): Builder;

    public function maxSalary(int $amount): Builder;
}