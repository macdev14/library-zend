<?php 

class Admin_Form_BookForm extends Zend_Form{
    public function init()
    {
        // Other form elements initialization...

        // Load author names and populate the 'autor' select element
        $authorTable = new Library_Model_DbTable_Author(); // Replace this with your author table model
        $authorNames = $authorTable->fetchAuthorNames(); // Method to fetch author names from the database
        
        $autorElement = $this->getElement('author_id'); // Assuming 'autor' is the name of your select element
        if($authorNames &&  $autorElement){
             $autorElement->setMultiOptions($authorNames);
        }
       
    }
}

?>