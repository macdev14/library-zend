<?php

class Library_Model_DbTable_Reservation extends Zend_Db_Table_Abstract
{
    protected $_name = 'reservations';

    public function fetchReservedBooks()
    {
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array('r' => 'reservations'), array('reservation_id' => 'id'))
            ->join(array('u' => 'users'), 'r.user_id = u.id', array('user_id' => 'id', 'username' => 'username'))
            ->join(array('b' => 'books'), 'r.book_id = b.ID', array('book_id' => 'ID', 'book_link' => 'Link', 'book_title' => 'Title'));

        // Debugging the generated SQL query
        // die(var_dump($select->__toString()));

        // Assuming $this is an instance of Zend_Db_Adapter_Abstract
        // $result = $this->fetchAll($select); // Execute the query to fetch the data

        return $select;
    }


    public function fetchReservedBooksByUserId($userId)
    {
        $select = $this->select()
        ->setIntegrityCheck(false)
            ->from(array('r' => 'reservations'), array('reservation_id' => 'id'))
            ->join(array('u' => 'users'), 'r.user_id = u.id', array('user_id' => 'id', 'username' => 'username'))
            ->join(array('b' => 'books'), 'r.book_id = b.ID', array('book_id' => 'ID', 'book_title' => 'Title', 'book_link' => 'Link'))
            ->where('r.user_id = ?', $userId);

        $rows = $this->fetchAll($select);
        $livros = array();
        foreach ($rows as $row) {
            $livros[$row['book_id']] = $row['book_title'];
        }
        return  $select;
    }

    public function fetchBooksNotReservedByUserId($userId)
    {
        $select = $this->select()
            ->from(array('b' => 'books'), array('book_id' => 'id', 'book_title' => 'title'))
            ->where('b.id NOT IN (SELECT r.book_id FROM reservations r WHERE r.user_id = ?)', $userId);

        $rows = $this->fetchAll($select);
        $livros = array();
        foreach ($rows as $row) {
            $livros[$row['id']] = $row['Title'];
        }
        return $livros;
    }

    public function fetchAllUnreservedBooks()
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('b' => 'books'), array('book_id' => 'id', 'book_title' => 'title'))
            ->joinLeft(array('r' => 'reservations'), 'b.id = r.book_id', array())
            ->where('r.book_id IS NULL');

        $rows = $this->fetchAll($select);

        $livros = array();
        foreach ($rows as $row) {
            $livros[$row['book_id']] = $row['book_title'];
        }

        return $livros;
    }

    public function fetchAllUnreservedBooksExceptReserved($reservation_id)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('b' => 'books'), array('book_id' => 'id', 'book_title' => 'title'))
            ->joinLeft(array('r' => 'reservations'), 'b.id = r.book_id AND r.id = ?', null)
            ->where('r.id IS NULL');

        $rows = $this->fetchAll($select);

        $livros = array();
        foreach ($rows as $row) {
            $livros[$row['book_id']] = $row['book_title'];
        }

        return $livros;
    }


    public function fetchReservationById($reservation_id)
    {
        $select = $this->select()
        ->setIntegrityCheck(false)
            ->from(array('r' => 'reservations'), array('reservation_id' => 'id'))
            ->join(array('b' => 'books'), 'r.book_id = b.id', array('book_id' => 'id', 'book_title' => 'title'))
            ->where('r.id = ?', $reservation_id);

        $rows = $this->fetchRow($select);
        // die(var_dump($rows['book_id']));
       
       
        return $rows;
    }

    public function isReservedByUser($userId, $bookId) {
        $select = $this->select()
            ->from('reservations')
            ->where('user_id = ?', $userId)
            ->where('book_id = ?', $bookId);
    
        $reservation = $this->fetchRow($select);
    
        return ($reservation !== null);
    }

    public function getReservationIdByBookId($bookId) {
        $select = $this->select()
            ->from('reservations')
            ->where('book_id = ?', $bookId);
    
        $reservation = $this->fetchRow($select);
        if($reservation){
            return $reservation['id'];
        }
        return $reservation;
    }
    
}
