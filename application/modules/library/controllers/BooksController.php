<?php

class Library_BooksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $bookList = new Library_Model_ListBooks(); 
        $bookList = $bookList->listBooks();

        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($bookList));
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page',1));
        $this->view->paginator = $paginator;
    }

    public function addAction()
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

    public function listAction()
    {
        $bookList = new Library_Model_DbTable_Books(); 
        $query = $this->_getParam('query');
        if($query){
        $result = $bookList->searchBooks($query);
        }else{
        $result = $bookList->getBooksWithAuthors();
        }
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($result));
        $paginator->setItemCountPerPage(3)->setCurrentPageNumber($this->_getParam('page',1));
        $this->view->paginator = $paginator;
        
    }



}









