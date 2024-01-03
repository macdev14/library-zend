<?php


class Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{
    private $_acl = null;
    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
    }
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {   
        $moduleName = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
       
            // verifica se possui permissao
            $role = Zend_Registry::get('role');
            $resource = $moduleName.':'.$controller;
            $isAllowed = $this->_acl->isAllowed($role, $resource, $action);
            if (!$isAllowed) {
                // redireciona ao login
                $request->setControllerName('authentication')->setActionName('login');
            }
        
    }
}
