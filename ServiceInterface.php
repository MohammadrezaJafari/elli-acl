<?php

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 9/29/15
 * Time: 7:57 PM
 */
namespace Ellie\Service\Acl;

use Zend\Mvc\MvcEvent;
interface ServiceInterface
{
    public function checkAcl(MvcEvent $e);

}