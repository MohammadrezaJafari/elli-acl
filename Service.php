<?php

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 9/29/15
 * Time: 7:56 PM
 */
namespace Ellie\Service\Acl;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;

class Service implements ServiceInterface
{
    protected $AuthService;
    protected $doctrineObj;
    protected $acl;
    public function __construct($doctrineObj,$AuthService){
        $this->AuthService = $AuthService;
        $this->doctrineObj = $doctrineObj;
        $acl = new Acl();
        $roles = include __DIR__ . "/config/module.acl.roles.php";
        $parentRole = null;
        foreach($roles as $role => $resources) {
            $role = new GenericRole($role);
            $acl->addRole($role, $parentRole);
            foreach ($resources as $resource) {
                if (!$acl->hasResource($resource)) {
                    $acl->addResource(new GenericResource($resource));
                    $acl->allow($role, $resource);
                }
            }
            $parentRole = $role;
        }
        $this->acl = $acl;
    }

    public function checkAcl(MvcEvent $e){
        $route = $e -> getRouteMatch() -> getMatchedRouteName();
        $action = $e->getRouteMatch()->getParam('action');
        $controller = $e->getRouteMatch()->getParam('controller');
        $resource = $route.'\\'.$controller.'\\'.$action;

        if (!$this-> acl ->hasResource($resource)) {$this->notAllowed($e->getTarget());}

        $roleName = "guest";

        if($this->AuthService->hasIdentity()){
            $user = $this->AuthService->getIdentity();
            $role = $this->doctrineObj->getRepository("User\Entity\Role")->find($user->getRole());
            $roleName = $role->getName();
        }

        if(!$this->acl->isAllowed($roleName,$resource)){$this->notAllowed($e->getTarget());}
    }

    protected function notAllowed($target){
        $target->getResponse()->setStatusCode(404);
        return;
    }

}