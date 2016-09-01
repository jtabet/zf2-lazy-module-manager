<?php

namespace LazyModuleManager\ModuleManager;

use Zend\ModuleManager\ModuleManager as ZendModuleManager;
use LazyModuleManager\ModuleManager\Proxy\ModuleProxy;

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
