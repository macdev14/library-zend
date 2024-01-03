<?php

class Admin_ReservationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        $authorTable = new Library_Model_DbTable_Reservation();

        $auth = Zend_Auth::getInstance();
        $authStorage = $auth->getStorage();
        $user_id = $authStorage->read()->id;
        $select = $authorTable->fetchReservedBooksByUserId($user_id);

        $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($select); // Create an adapter using Zend_Db_Select

        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/reservation.ini', 'create');
        $form = new Admin_Form_ReservationForm($config);
        $create_action = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'create']);
        $form->setAction($create_action);
        $this->view->form = $form;
    }

    public function createAction()
    {
        // Create an instance of the form
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/reservation.ini', 'create');
        $form = new Admin_Form_ReservationForm($config);

        // Check if the form was submitted (POST request)
        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->getPost(); // Get form data
            $userIdNaoExiste = !isset($formData['user_id']) || empty($formData['user_id']);
           
            if ($userIdNaoExiste) {
                $formData['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;
            }

            // Check if the form data is valid
            if ($form->isValid($formData)) {
                // Get values from the form
                // Instantiate your model (assuming 'Library_Model_DbTable_Author' represents your Author table)
                $authorTable = new Library_Model_DbTable_Reservation();

                // Prepare data to be inserted from the form values
                $data = array(
                    'book_id' => $formData['book_id'],
                    'user_id' => $formData['user_id'],
                    // Add other columns and their respective data here
                );

                // Insert the data into the database
                $authorTable->insert($data);
                if ($userIdNaoExiste) {
                    $books_url = $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
                    return $this->redirect($books_url);
                }
                $books_url = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'index']);
                return $this->redirect($books_url);
            } else {
                // Form validation failed, re-populate the form with submitted data
                print_r($form->getErrors());
                // $this->_helper->redirector('author/add');
            }
        }
        $add_url = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'add']);
        return $this->redirect($add_url);
    }

    public function editAction()
    {
        // Assuming you have an author ID (replace 123 with the actual ID)
        $authorId = $this->_getParam('id');
        // Fetch the author's data from the database
        $authorTable = new Library_Model_DbTable_Reservation();
        $authorData = $authorTable->find($authorId)->current(); // Assuming 'id' is the primary key


        if (!$authorData) {
            // $this->getResponse()->setHttpResponseCode(404);
            $url =  $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'index']);
            return $this->redirect($url);
        }
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/reservation.ini', 'edit');
        $form = new Admin_Form_ReservationForm($config, $authorId);
        $update_url = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'update']);
        $form->setAction($update_url);
        $form->populate($authorData->toArray());
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            $formData = $request->getPost();
            $authorId = $formData['id'];
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/reservation.ini', 'edit');
            $form = new Admin_Form_ReservationForm($config, $authorId);
            $authorTable = new Library_Model_DbTable_Reservation();

            if ($form->isValid($formData)) {

                $url = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'edit', 'id' => $authorId]);

                $authorTable = new Library_Model_DbTable_Reservation();
                $authorData = $authorTable->find($authorId)->current();

                if (!$authorData) {
                    $this->getResponse()->setHttpResponseCode(404);
                    return;
                }
                $authorData->setFromArray($form->getValues());
                $authorData->save();
                return $this->redirect($url);
            } else {
                $url =  $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'edit', 'id' => $formData['id']]);
                return $this->redirect($url);
            }
        }
        $url =  $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'add']);
        return $this->redirect($url);
    }

    public function deleteAction()
    {
        $authorId = $this->_getParam('id');
        $inline =  $this->_getParam('inline');
        $authorTable = new Library_Model_DbTable_Reservation(); // Replace this with your author table model
        $where = $authorTable->getAdapter()->quoteInto('id = ?', $authorId);
        $authorTable->delete($where);
        if($inline){
        $url = $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
        }
        else{
        $url = $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'index']);
        }
        return $this->redirect($url);
    }

    public function listAction()
    {

        $authorTable = new Library_Model_DbTable_Reservation();

        $select = $authorTable->fetchReservedBooks();

        $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($select); // Create an adapter using Zend_Db_Select

        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }
}
