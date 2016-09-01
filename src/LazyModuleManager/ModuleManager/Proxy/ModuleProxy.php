<?php

namespace LazyModuleManager\ModuleManager\Proxy;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManagerInterface;

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

        return call_user_func_array([$this->instance, $method], $arguments);
    }
    
    /**
     * 
     * @return object
     * @throws Exception\RuntimeException
     */
    protected function instantiate()
    {
        $moduleName = $this->moduleEvent->getModuleName();
        $class      = $moduleName . '\Module';

        if (!class_exists($class)) {
            $result = false;
        } else {
            $result = new $class;
        }

        if (!is_object($result)) {
            throw new Exception\RuntimeException(sprintf(
                'Module (%s) could not be initialized.',
                $this->event->getModuleName()
            ));
        }

        return $result;
    }
}
