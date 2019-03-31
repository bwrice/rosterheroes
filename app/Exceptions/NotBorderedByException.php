<?php

namespace App\Exceptions;

use App\Domain\Models\Province;

class NotBorderedByException extends \RuntimeException
{
    protected $province;

    protected $border;

    public function setProvinces(Province $province, Province $border)
    {
        $this->message = $border->name . ' does not border ' . $province->name;
        $this->province = $province;
        $this->border = $border;
        return $this;
    }

    /**
     * @return \App\Domain\Models\Province
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return Province
     */
    public function getBorder()
    {
        return $this->border;
    }

}
