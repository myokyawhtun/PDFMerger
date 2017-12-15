<?php
namespace matiasiglesias;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package matiasiglesias
 * @author Matias Iglesias <matiasiglesias@meridiem.com.ar>
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

}
