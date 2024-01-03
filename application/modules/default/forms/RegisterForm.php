<?php 

class Default_Form_RegisterForm extends Zend_Form{
    public function init()
    {
        $roleElement = $this->getElement('role'); 
        if($roleElement){
            $roles = ['admins'=>'Administrador','users'=>'Usuário'];
            $roleElement->setMultiOptions($roles);
        }
    }
}

?>