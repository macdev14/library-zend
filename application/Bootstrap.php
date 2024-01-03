<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_acl = null;

    /**
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array('namespace' => '',
         'basePath' => APPLICATION_PATH.'/modules/default'));
        $autoloader->addResourceType('plugin', 'plugins', 'Plugin');
        $fc = Zend_Controller_Front::getInstance();
        
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $role = Zend_Auth::getInstance()->getStorage()->read()->role;
            Zend_Registry::set('role', $role);
        }else{
            Zend_Registry::set('role', 'guests');
        }
        $this->_acl = new Model_LibraryAcl;
        $this->_auth = Zend_Auth::getInstance();
        $fc->registerPlugin(new Plugin_AccessCheck($this->_acl, $this->_auth));
        return $autoloader;
    }

    public function _initViewHelpers(){
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $view->setHelperPath(APPLICATION_PATH.'/helpers','');

        $view->headTitle()->setSeparator(' - ')->headTitle('Biblioteca');
        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . "/configs/navigation.xml", 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        $role = Zend_Registry::get('role');
        $view->navigation($navContainer)->setAcl($this->_acl)->setRole($role);
    }

}
