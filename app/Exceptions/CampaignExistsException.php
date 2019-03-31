<?php

namespace App\Exceptions;

use App\Domain\Models\Campaign;
use Exception;
use Throwable;

class CampaignExistsException extends Exception
{
    /**
     * @var \App\Domain\Models\Campaign
     */
    private $campaign;

    public function __construct(Campaign $campaign, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Campaign already exists';
        parent::__construct($message, $code, $previous);
        $this->campaign = $campaign;
    }

    /**
     * @return \App\Domain\Models\Campaign
     */
    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }
}
