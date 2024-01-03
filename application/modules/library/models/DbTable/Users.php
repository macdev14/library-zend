<?php

class Library_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    // public function __construct()
    // {
    //     parent::__construct(['dependentTables' => ['Library_Model_DbTable_Books']]);
    // }

    public function get_user($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }


    public function addUser($username, $password,  $role)
    {
        $data = array(
            'username' => $username,
            'password' => $password,
            'role'=>$role
        );
        $this->insert($data);
    }

    public function editUser($id, $username, $password)
    {
        $data = array(
            'username' => $username,
            'password' => $password
        );
        $this->update($data, 'id = ' . (int)$id);
    }

    public function deleteUser($id)
    {
        $this->delete('id =' . (int)$id);
    }

    public function fetchUsersNames()
    {
        $select = $this->select()->from('users', array('id', 'username')); // Assuming 'authors' is your table name
        $rows = $this->fetchAll($select);
        $usersNames = array();
        foreach ($rows as $row) {
            $usersNames[$row['id']] = $row['username'];
        }
        return $select;
    }

    public function fetchUsersNamesArray()
    {
        $select = $this->select()->from('users', array('id', 'username')); // Assuming 'authors' is your table name
        $rows = $this->fetchAll($select);
        $usersNames = array();
        foreach ($rows as $row) {
            $usersNames[$row['id']] = $row['username'];
        }
        return $usersNames;
    }

}
