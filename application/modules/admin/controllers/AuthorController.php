<?php

class Admin_AuthorController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $authorTable = new Library_Model_DbTable_Author();
        $select = $authorTable->select();

        $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($select); // Create an adapter using Zend_Db_Select

        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/author.ini', 'create');
        $form = new Admin_Form_AuthorForm($config);
        $create_action = $this->_helper->url->url(['controller' => 'author', 'module' => 'admin', 'action' => 'create']);
        $form->setAction($create_action);
        $this->view->form = new Admin_Form_AuthorForm($config);
    }

    public function createAction()
    {
        $form = new Admin_Form_AuthorForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->getPost(); // Get form data

            if ($form->isValid($formData)) {

                $authorTable = new Library_Model_DbTable_Author();


                $data = array(
                    'nome' => $formData['nome'],
                    'link' => $formData['link'],
                );

                // Insert the data into the database
                $authorTable->insert($data);

                // Optionally, redirect to another page after successful insertion
                $this->_helper->redirector('/'); // Redirect to index page after insertion
            } else {

                print_r($form->getErrors());
            }
        }
        $this->view->form = $form;
    }


    public function editAction()
    {
        $authorId = $this->_getParam('id');

        $authorTable = new Library_Model_DbTable_Author();
        $authorData = $authorTable->find($authorId)->current(); 

        if (!$authorData) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/author.ini', 'edit');
        $form = new Admin_Form_AuthorForm($config);
        $update_url = $this->_helper->url->url(['controller' => 'author', 'module' => 'admin', 'action' => 'update']);
        $form->setAction($update_url);
        $form->populate($authorData->toArray());
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/author.ini', 'edit');
        $form = new Admin_Form_AuthorForm($config);
        $authorTable = new Library_Model_DbTable_Author();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $formData = $request->getPost();
            if ($form->isValid($formData)) {
                $authorId = $formData['id'];
                $url = $this->_helper->url->url(['controller' => 'author', 'module' => 'admin', 'action' => 'edit', 'id' => $authorId]);
               
                $authorTable = new Library_Model_DbTable_Author();
                $authorData = $authorTable->find($authorId)->current();

                if (!$authorData) {
                    $url =  $this->_helper->url->url(['controller'=>'author','module'=>'admin','action'=>'index']);
                    return $this->redirect($url);
                }
                $authorData->setFromArray($form->getValues());
                $authorData->save();
                return $this->redirect($url); 
            } else {
                $url =  $this->_helper->url->url(['controller' => 'author', 'module' => 'admin', 'action' => 'edit', 'id' => $formData['id']]);
                return $this->redirect($url);
            }
        }
        $url =  $this->_helper->url->url(['controller' => 'author', 'module' => 'admin', 'action' => 'add']);

        return $this->redirect($url);
    }


    public function deleteAction()
    {
        // action body
    }
}
