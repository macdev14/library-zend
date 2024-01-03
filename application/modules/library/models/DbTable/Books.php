<?php

class Library_Model_DbTable_Books extends Zend_Db_Table_Abstract
{
    protected $_name = 'books';

    public function getBooksWithAuthors()
    {
        $select = $this->select()
            ->setIntegrityCheck(false) // To allow joining with other tables
            ->from(array('b' => $this->_name)) // 'b' is an alias for the 'books' table
            ->join(array('a' => 'author'), 'b.author_id = a.id', array('author_name' => 'a.nome')) // 'a' is an alias for the 'authors' table
            ->join(array('u' => 'users'), 'b.user_id = u.id', array('user_id' => 'id', 'username' => 'username'));
        return $select;
    }

    public function getBooksWithAuthorsByUserID($userId)
    {
        $select = $this->select()
            ->setIntegrityCheck(false) // To allow joining with other tables
            ->from(array('b' => $this->_name)) // 'b' is an alias for the 'books' table
            ->join(array('a' => 'author'), 'b.author_id = a.id', array('author_name' => 'a.nome')) // 'a' is an alias for the 'authors' table
            ->join(array('u' => 'users'), 'b.user_id = u.id', array('user_id' => 'id', 'username' => 'username'))
            ->where('u.id = ?', $userId);
        return $select;
    }

    public function searchBooks($searchValue)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('b' => $this->_name))
            ->join(array('a' => 'author'), 'b.author_id = a.id', array('author_name' => 'a.nome'))
            ->join(array('u' => 'users'), 'b.user_id = u.id', array('user_id' => 'id', 'username' => 'username'));

        if ($searchValue !== null) {
            $select->where('b.Title LIKE ?', '%' . $searchValue . '%')
                ->orWhere('a.nome LIKE ?', '%' . $searchValue . '%')
                ->orWhere('u.username LIKE ?', '%' . $searchValue . '%');
        }
        return $select;
    }
}
