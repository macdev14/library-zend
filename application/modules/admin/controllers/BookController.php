<?php

class Admin_BookController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $authStorage = $auth->getStorage();
        $user_id = $authStorage->read()->id;
        $bookList = new Library_Model_DbTable_Books();
        $bookList = $bookList->getBooksWithAuthorsByUserID($user_id);
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($bookList));
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/book.ini', 'create');
        $form = new Admin_Form_BookForm($config);
        $create_action = $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'create']);
        $form->setAction($create_action);
        $this->view->form = $form;
    }

    public function createAction()
    {
        // Create an instance of the form
        $form = new Admin_Form_BookForm();
        $create_url = $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'create']);
        $form->setAction($create_url);

        // Check if the form was submitted (POST request)
        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->getPost(); // Get form data


            // Check if the form data is valid
            if ($form->isValid($formData)) {
                // Get values from the form


                // Instantiate your model (assuming 'Library_Model_DbTable_Author' represents your Author table)
                $authorTable = new Library_Model_DbTable_Books();
                $auth = Zend_Auth::getInstance();
                $authStorage = $auth->getStorage();
                $user_id = $authStorage->read()->id;
                // Prepare data to be inserted from the form values
                $data = array(
                    'Title' => $formData['Title'],
                    'Link' => $formData['Link'],
                    'user_id' => $user_id,
                    'author_id' => $formData['author_id'],
                    // Add other columns and their respective data here
                );

                // Insert the data into the database
                $authorTable->insert($data);

                $url = $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
                // Optionally, redirect to another page after successful insertion
                return $this->redirect($url);
            } else {
                // Form validation failed, re-populate the form with submitted data
                print_r($form->getErrors());
                // $this->_helper->redirector('author/add');
            }
            $this->redirect($create_url);
        }

        // Pass the form to the view
        
    }

    public function editAction()
    {
        // Assuming you have an author ID (replace 123 with the actual ID)
        $authorId = $this->_getParam('id');

        $authorTable = new Library_Model_DbTable_Books();
        $authorData = $authorTable->find($authorId)->current();
        if (!$authorData) {

            $url =  $this->_helper->url->url(['controller' => 'reservation', 'module' => 'admin', 'action' => 'index']);
            return $this->redirect($url);
        }
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/book.ini', 'edit');
        $form = new Admin_Form_BookForm($config);
        $update_url = $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'update']);
        $form->setAction($update_url);
        $form->populate($authorData->toArray());
        $this->view->form = $form;
    }


    public function updateAction()
    {

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms/book.ini', 'edit');
        $form = new Admin_Form_BookForm($config);
        $authorTable = new Library_Model_DbTable_Author();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $formData = $request->getPost();

            if ($form->isValid($formData)) {

                $authorId = $formData['ID'];
                $url = $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'edit', 'id' => $authorId]);

                $authorTable = new Library_Model_DbTable_Books();
                $authorData = $authorTable->find($authorId)->current();

                if (!$authorData) {

                    $url =  $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);
                    return $this->redirect($url);
                }

                // Update the author's data with the submitted form data
                $authorData->setFromArray($form->getValues());
                $authorData->save(); // Save the updated author data

                // Optionally, redirect to another page after successful update
                return $this->redirect($url); // Redirect to index page after update
            } else {
                $url =  $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'edit', 'id' => $formData['id']]);
                return $this->redirect($url);
            }
        }
        $url =  $this->_helper->url->url(['controller' => 'book', 'module' => 'admin', 'action' => 'add']);

        return $this->redirect($url);
    }



    public function deleteAction()
    {
        $authorId = $this->_getParam('id');


        $authorTable = new Library_Model_DbTable_Books(); // Replace this with your author table model
        $where = $authorTable->getAdapter()->quoteInto('ID = ?', $authorId);
        $authorTable->delete($where);
        $url =  $this->_helper->url->url(['controller' => 'books', 'module' => 'library', 'action' => 'list']);

        return $this->redirect($url);
        // Redirect or perform other actions after deletion
    }
}
