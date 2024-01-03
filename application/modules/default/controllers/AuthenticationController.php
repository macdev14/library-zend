<?php

class Default_AuthenticationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $authenticated = Zend_Auth::getInstance()->hasIdentity();
        
        $index = $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
        if ($authenticated) {
            return $this->redirect($index);
        }
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/register.ini', 'create');
        $form = new Default_Form_RegisterForm($config);
    
        $request = $this->getRequest();
        $isPost = $request->isPost();
        if ($isPost) {
            $getPost = $this->_request->getPost();
            $isValid = $form->isValid($getPost);

            if ($isValid) {
                $userTable = new Library_Model_DbTable_Users();
                $authAdapter = $this->getAuthAdapter();
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $role = $form->getValue('role');
                $confirm_password = $form->getValue('confirm_password');
                $valido = $password == $confirm_password;
                if (!$valido) {
                    $this->view->errorMessage = 'Usuário ou senha inválido';
                }
                $userTable->addUser($username, $password, $role);
                $authAdapter->setIdentity($username);
                $authAdapter->setCredential($password);

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
                    return $this->redirect($index);
                } else {
                    $this->view->errorMessage = 'Usuário ou senha inválido';
                   
                }
            }
            $this->view->errorMessage = 'Usuário ou senha inválidos';
           
        }
        
        $register_page = $this->_helper->url->url(['controller' => 'authentication', 'module' => 'default', 'action' => 'add']);
        $form->setAction($register_page);
        $this->view->form = $form;
    }
    public function loginAction()
    {
        // action body
        $authenticated = Zend_Auth::getInstance()->hasIdentity();
        $index = $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
        if ($authenticated) {

            return $this->redirect($index);
        }
        $form = new Default_Form_LoginForm();
        $request = $this->getRequest();
        $isPost = $request->isPost();
        if ($isPost) {
            $getPost = $this->_request->getPost();
            $isValid = $form->isValid($getPost);

            if ($isValid) {

                $authAdapter = $this->getAuthAdapter();
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $authAdapter->setIdentity($username);
                $authAdapter->setCredential($password);

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    return $this->redirect($index);
                } else {
                    $this->view->errorMessage = 'Username or password is wrong';
                }
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        // action body
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect('authentication/login');
    }

    private function getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter()
        );

        $authAdapter->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');
        return $authAdapter;
    }
}
