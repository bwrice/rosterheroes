<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\MatcherAbstract;

class EloquentMatcher extends MatcherAbstract
{

    /**
     * Check if the actual value matches the expected.
     * Actual passed by reference to preserve reference trail (where applicable)
     * back to the original method parameter.
     *
     * @param mixed $actual
     * @return bool
     */
    public function match(&$actual)
    {
        if (! $actual instanceof Model) {
            throw new \InvalidArgumentException('actual most be an instance of ' . Model::class);
        }
        /** @var Model $expected */
        $expected = $this->_expected;
        return $expected->getKey() === $actual->getKey();
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Model===Model>';
    }

    /**
     * @param Model $model
     * @return static
     */
    public static function withExpected(Model $model)
    {
        return new static($model);
    }
}
