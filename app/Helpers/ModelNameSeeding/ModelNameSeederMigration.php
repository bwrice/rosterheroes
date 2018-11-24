<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 11/18/18
 * Time: 8:10 PM
 */

namespace App\Helpers\ModelNameSeeding;


use Illuminate\Database\Migrations\Migration;

abstract class ModelNameSeederMigration extends Migration
{
    /**
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * @return array
     */
    abstract protected function getSeedNames(): array;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modelClass = $this->getModelClass();

        foreach( $this->getSeedNames() as $name ) {
            $modelClass::firstOrCreate([
                'name' => $name
            ]);
        }
    }
}