<?php

namespace Laravel\Nova\Tests\Feature;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Tests\IntegrationTest;

class SelectTest extends IntegrationTest
{
    public function setUp() : void
    {
        parent::setUp();
    }

    public function test_select_fields_can_have_custom_display_callback()
    {
        $field = Select::make('Sizes')->options([
            'L' => 'Large',
            'S' => 'Small',
        ])->displayUsingLabels();

        $field->resolve((object) ['size' => 'L'], 'size');
        $this->assertEquals('L', $field->value);

        $field->resolveForDisplay((object) ['size' => 'L'], 'size');
        $this->assertEquals('Large', $field->value);
    }

    public function test_select_fields_can_use_callable_array_as_options()
    {
        $field = Select::make('Sizes')->options([
            'DateTimeZone', 'listIdentifiers',
        ]);

        $field->resolve((object) ['timezone' => 'America/Chicago'], 'timezone');
        $this->assertEquals('America/Chicago', $field->value);

        $field->resolveForDisplay((object) ['timezone' => 'America/Chicago'], 'timezone');
        $this->assertEquals('America/Chicago', $field->value);
    }

    public function test_select_fields_can_accept_closures_as_options()
    {
        $field = Select::make('Sizes')->options(function () {
            return [
                'L' => 'Large',
                'S' => 'Small',
            ];
        })->displayUsingLabels();

        $field->resolve((object) ['size' => 'L'], 'size');
        $this->assertEquals('L', $field->value);

        $field->resolveForDisplay((object) ['size' => 'L'], 'size');
        $this->assertEquals('Large', $field->value);
    }
}
