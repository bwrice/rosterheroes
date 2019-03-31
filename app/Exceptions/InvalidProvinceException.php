<?php

namespace App\Exceptions;

use App\Domain\Models\Province;
use Exception;
use Throwable;

class InvalidProvinceException extends Exception
{
    /**
     * @var \App\Domain\Models\Province
     */
    private $invalidProvince;

    public function __construct(Province $invalidProvince, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "Cannot perform action on Province: " . $invalidProvince->name;
        parent::__construct($message, $code, $previous);
        $this->invalidProvince = $invalidProvince;
    }

    /**
     * @return \App\Domain\Models\Province
     */
    public function getInvalidProvince(): Province
    {
        return $this->invalidProvince;
    }
}
