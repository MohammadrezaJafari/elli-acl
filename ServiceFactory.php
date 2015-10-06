<?php

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 9/29/15
 * Time: 7:57 PM
 */
namespace Ellie\Service\Acl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $AuthService = $serviceLocator->get("Ellie\Service\Authentication");
        $doctrineService = $serviceLocator->get('Doctrine\ORM\EntityManager');
       // die(var_dump($doctrineService));

        return new Service($doctrineService,$AuthService);
    }
}