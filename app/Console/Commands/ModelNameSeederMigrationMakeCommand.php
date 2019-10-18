<?php

namespace App\Console\Commands;

use App\Helpers\ModelNameSeeding\ModelNameSeederMigrationCreator;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Composer;

class ModelNameSeederMigrationMakeCommand extends MigrateMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mns-migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model name seeder migration';

    /**
     * ModelNameSeederMigrationMakeCommand constructor.
     * @param ModelNameSeederMigrationCreator $creator
     * @param Composer $composer
     */
    public function __construct(ModelNameSeederMigrationCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);
    }
}
