<?php
namespace MteBase\Module;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

abstract class AbstractModule implements 
    AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
     * @var \ReflectionObject
     */
    private $_reflectionObject = null;
    
    /**
     * @return ReflectionObject
     */
    private function _getReflectionObject()
    {
        if (is_null($this->_reflectionObject)) {
            $this->_reflectionObject = new \ReflectionObject($this);
        }
        
        return $this->_reflectionObject;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return dirname($this->_getReflectionObject()->getFileName());
    }
    
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->_getReflectionObject()->getNamespaceName();
    }
    
    /**
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include $this->getDir() . '/config/module.config.php';
    }
    
    /**
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                $this->getDir() . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->getNamespace() => $this->getDir() . '/src/' . $this->getNamespace(),
                ),
            ),
        );
    }
}
