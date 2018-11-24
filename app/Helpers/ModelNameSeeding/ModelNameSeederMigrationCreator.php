<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 11/18/18
 * Time: 9:04 PM
 */

namespace App\Helpers\ModelNameSeeding;


use Illuminate\Database\Migrations\MigrationCreator;

class ModelNameSeederMigrationCreator extends MigrationCreator
{
    /**
     * @param string $table
     * @param bool $create
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($table, $create)
    {
        return $this->files->get(__DIR__ . '/modelNameSeederMigration.stub' );
    }
}