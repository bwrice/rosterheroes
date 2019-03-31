<?php

namespace App\Exceptions;


use App\Domain\Collections\PositionCollection;
use Illuminate\Support\Collection;

class InvalidPositionsException extends \RuntimeException
{
    protected $validPositions;

    protected $givenPositions;

    public function setPositions(PositionCollection $validPositions, PositionCollection $givenPositions)
    {
        $this->message .= "Allowed Positions: " . $validPositions->implode('name', ', ');
        $this->message .= " Given Positions: " . $givenPositions->implode('name', ', ');
        $this->validPositions = $validPositions;
        $this->givenPositions = $givenPositions;
    }

    /**
     * @return \App\Domain\Collections\PositionCollection
     */
    public function getValidPositions()
    {
        return $this->validPositions;
    }

    /**
     * @return \App\Domain\Collections\PositionCollection
     */
    public function getGivenPositions()
    {
        return $this->givenPositions;
    }


}
