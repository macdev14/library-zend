<?php

class Admin_Form_ReservationForm extends Zend_Form
{
    public $reservationid = null;
    public function __construct($config = array(), $reservationid = null)
    {
        if ($reservationid !== null) {
            $this->reservationid = $reservationid;
        }
        parent::__construct($config);



        // ... (other form element initializations)
    }
    public function init()
    {
       
        $bookTable = new Library_Model_DbTable_Books(); // Replace this with your author table model
        $bookFetch = $bookTable->fetchAll(); // Method to fetch author names from the database
        $books = [];

        foreach ($bookFetch as $book) {
            $books[$book['ID']] = $book['Title'];
        }
        $autorElement = $this->getElement('book_id'); // Assuming 'autor' is the name of your select element
        $reservationTable = new Library_Model_DbTable_Reservation(); // Replace this with your
        $unreserved = $reservationTable->fetchAllUnreservedBooks(); // Method to fetch unreserved books from the database table
        if ($this->reservationid !== null) {
            $reservation = $reservationTable->fetchReservationById($this->reservationid);
            $unreserved[$reservation['book_id']] = $reservation['book_title'];
        }
        if ($unreserved && $autorElement) {
            $autorElement->setMultiOptions($unreserved);
        }

        $userTable = new Library_Model_DbTable_Users(); // Replace this with your author table model
        $users = $userTable->fetchUsersNamesArray(); // Method to fetch author names from the database
        
        $userElement = $this->getElement('user_id'); // Assuming 'autor' is the name of your select element
        if ($users && $userElement) {
            $userElement->setMultiOptions($users);
        }
    }
}
