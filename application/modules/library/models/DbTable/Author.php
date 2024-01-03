<?php

class Library_Model_DbTable_Author extends Zend_Db_Table_Abstract
{
    protected $_name = 'author';

    // public function __construct()
    // {
    //     parent::__construct(['dependentTables' => ['Library_Model_DbTable_Books']]);
    // }

    public function get_author($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }


    public function addAuthor($nome,$link)
    {
        $data = array(
            'nome' => $nome,
            'link' => $link
        );
        $this->insert($data);
    }

    public function editAuthor($id, $nome, $link)
    {
        $data = array(
            'nome' => $nome,
            'link' => $link
        );
        $this->update($data, 'id = ' . (int)$id);
    }

    public function deleteAuthor($id)
    {
        $this->delete('id =' . (int)$id);
    }

    public function fetchAuthorNames()
    {
        $select = $this->select()->from('author', array('id', 'nome')); // Assuming 'authors' is your table name
        $rows = $this->fetchAll($select);
        $authorNames = array();
        foreach ($rows as $row) {
            $authorNames[$row['id']] = $row['nome'];
        }
        return $authorNames;
    }
}
