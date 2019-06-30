<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotEnoughEssenceException extends \RuntimeException
{
    protected $essenceAvailable;

    protected $essenceNeeded;

    public function setAttributes($essenceAvailable, $essenceNeeded)
    {
        $this->message = $essenceAvailable . " essence available, but " . $essenceNeeded . " essence is needed";
        $this->essenceAvailable = $essenceAvailable;
        $this->essenceNeeded = $essenceNeeded;
    }

    /**
     * @return int
     */
    public function getEssenceAvailable()
    {
        return $this->essenceAvailable;
    }

    /**
     * @return int
     */
    public function getEssenceNeeded()
    {
        return $this->essenceNeeded;
    }
}
