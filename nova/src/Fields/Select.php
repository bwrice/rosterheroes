<?php

namespace Laravel\Nova\Fields;

use Illuminate\Support\Arr;

class Select extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-field';

    /**
     * Set the options for the select menu.
     *
     * @param  array|\Closure|\Illuminate\Support\Collection
     * @return $this
     */
    public function options($options)
    {
        if (is_callable($options) || $this->isCallableArray($options)) {
            $options = $options();
        }

        return $this->withMeta([
            'options' => collect($options ?? [])->map(function ($label, $value) {
                return is_array($label) ? $label + ['value' => $value] : ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }

    protected function isCallableArray($options)
    {
        return $this->isCountable($options) && ! Arr::isAssoc($options) && method_exists($options[0], $options[1]);
    }

    protected function isCountable($options)
    {
        return is_countable($options) && count($options) === 2;
    }

    /**
     * Display values using their corresponding specified labels.
     *
     * @return $this
     */
    public function displayUsingLabels()
    {
        return $this->displayUsing(function ($value) {
            return collect($this->meta['options'])
                    ->where('value', $value)
                    ->first()['label'] ?? $value;
        });
    }
}
