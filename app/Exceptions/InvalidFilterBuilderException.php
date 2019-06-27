<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 10:02 PM
 */

namespace App\Exceptions;


use Illuminate\Database\Eloquent\Builder;
use Throwable;

class InvalidFilterBuilderException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $filterClass;
    /**
     * @var string
     */
    private $requiredInstance;
    /**
     * @var Builder
     */
    private $builder;

    public function __construct(string $filterClass, string $requiredInstance, Builder $builder, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->buildMessage($filterClass, $requiredInstance, $builder);
        $this->filterClass = $filterClass;
        $this->builder = $builder;
        $this->requiredInstance = $requiredInstance;
        parent::__construct($message, $code, $previous);
    }

    protected function buildMessage(string $filterClass, string $requiredInstance, Builder $builder)
    {
        $message = $filterClass . ' requires a builder object that\'s an instance of ' . $requiredInstance;
        $message .= ' but instance of ' . get_class($builder) . ' given.';
        return $message;
    }

    /**
     * @return string
     */
    public function getFilterClass(): string
    {
        return $this->filterClass;
    }

    /**
     * @return string
     */
    public function getRequiredInstance(): string
    {
        return $this->requiredInstance;
    }
}