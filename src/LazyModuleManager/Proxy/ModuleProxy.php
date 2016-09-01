<?php

namespace LazyModuleManager\Proxy;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\ModuleEvent;

class ModuleProxy
{
    /**
     *
     * @var ModuleManagerInterface Required to instantiate the module
     */
    protected $moduleManager;
    
    /**
     *
     * @var EventInterface Required to instantiate the module
     */
    protected $moduleEvent;
    
    /**
     *
     * @var object The module instance
     */
    protected $instance;
    
    /**
     * 
     * @param EventInterface $moduleEvent
     * @param ModuleManagerInterface $moduleManager
     */
    public function __construct(EventInterface $moduleEvent, ModuleManagerInterface $moduleManager)
    {
        $this->moduleEvent = $moduleEvent;
        $this->moduleManager = $moduleManager;
    }
    
    /**
     * 
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (empty($this->instance)) {
            $this->instance = $this->instantiate();
        }
        
        return call_user_method_array($method, $this->instance, $arguments);
    }
    
    /**
     * 
     * @return object
     * @throws Exception\RuntimeException
     */
    protected function instantiate()
    {
        $result = $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, $this->moduleManager, $this->event, function ($r) {
            return (is_object($r));
        });

        $module = $result->last();
        if (!is_object($module)) {
            throw new Exception\RuntimeException(sprintf(
                'Module (%s) could not be initialized.',
                $this->event->getModuleName()
            ));
        }

        return $module;
    }
}
