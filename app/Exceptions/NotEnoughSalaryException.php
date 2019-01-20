<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotEnoughSalaryException extends \RuntimeException
{
    protected $salaryAvailable;

    protected $salaryNeeded;

    public function setSalaries($salaryAvailable, $salaryNeeded)
    {
        $this->message = $salaryAvailable . " salary available, but " . $salaryNeeded . " salary is needed";
        $this->salaryAvailable = $salaryAvailable;
        $this->salaryNeeded = $salaryNeeded;
    }

    /**
     * @return int
     */
    public function getSalaryAvailable()
    {
        return $this->salaryAvailable;
    }

    /**
     * @return int
     */
    public function getSalaryNeeded()
    {
        return $this->salaryNeeded;
    }
}
