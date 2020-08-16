<?php


namespace App\Admin\Content\Actions;


use App\Exceptions\SyncContentException;
use App\Facades\Content;

abstract class SyncContent
{
    public const ATTACKS_DEPENDENCY = 'attacks';

    protected $dependencies = [];

    /**
     * @throws SyncContentException
     */
    protected function checkDependencies()
    {
        foreach ($this->dependencies as $dependency) {
            switch ($dependency) {
                case self::ATTACKS_DEPENDENCY;
                    if (Content::unSyncedAttacks()->isNotEmpty()) {
                        throw new SyncContentException("Attacks dependency not synced", SyncContentException::CODE_ATTACKS_NOT_SYNCED);
                    }
                    break;
                default:
                    throw new \Exception("Unknown dependency: " . $dependency);
            }
        }
    }
}
