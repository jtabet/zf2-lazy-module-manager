<?php

namespace LazyModuleManager;

use Zend\ModuleManager\ModuleManager as ZendModuleManager;
use LazyModuleManager\Proxy\ModuleProxy;

class ModuleManager extends ZendModuleManager
{
    /**
     * {@inheritdoc}
     */
    protected function loadModuleByName($event)
    {
        return new ModuleProxy($event, $this);
    }
}
