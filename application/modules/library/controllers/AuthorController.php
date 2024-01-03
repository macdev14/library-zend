<?php

class Library_AuthorController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $authorTable = new Library_Model_DbTable_Author(); 
        $select = $authorTable->select(); 

        $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($select); // Create an adapter using Zend_Db_Select
    
        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }
    

    public function createAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }


}







