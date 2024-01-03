<?php
class Model_LibraryAcl extends Zend_Acl {
    public function __construct() {
       $this->addRole(new Zend_Acl_Role('guests'));
       $this->addRole(new Zend_Acl_Role('users'), 'guests');
       $this->addRole(new Zend_Acl_Role('admins'), 'users');
       
       $this->addResource(new Zend_Acl_Resource('library'))
       ->addResource(new Zend_Acl_Resource('library:books'),'library');

       $this->addResource(new Zend_Acl_Resource('admin'))
       ->addResource(new Zend_Acl_Resource('admin:book'),'admin')
       ->addResource(new Zend_Acl_Resource('admin:author'),'admin')
       ->addResource(new Zend_Acl_Resource('admin:reservation'),'admin')
       ->addResource(new Zend_Acl_Resource('admin:users'),'admin')
       ;

       $this->addResource(new Zend_Acl_Resource('default'))
       ->addResource(new Zend_Acl_Resource('default:authentication'),'default')
       ->addResource(new Zend_Acl_Resource('default:index'),'default')
       ->addResource(new Zend_Acl_Resource('default:error'),'default');

       $this->allow('guests','default:authentication', 'login');
       $this->allow('guests','default:error', 'error');
       $this->allow('guests','default:authentication', 'add');
       $this->allow('guests','library:books','list');

       $this->deny('users','default:authentication', 'login');
       $this->deny('users','default:authentication', 'add');
       $this->allow('users','default:index', 'index');
       $this->allow('users','default:authentication', 'logout');
       $this->allow('users','library:books',array('index','list'));

       $this->allow('admins','admin:users', array('index','add','edit','delete','update','create'));
       $this->allow('admins','admin:book', array('index','add','edit','delete','update','create'));
       $this->allow('admins','admin:author', array('index','add','edit','delete','update','create'));
       $this->allow('users','admin:book', array('index','add','edit','delete','update','create'));
       $this->allow('users','admin:author', array('index','add','edit','delete','update','create'));
        
       $this->allow('users','admin:reservation', array('index','add','edit','delete','update','create'));
       $this->allow('admins','admin:reservation', array('index','add','edit','delete','update','create','list'));
        

    }
}
