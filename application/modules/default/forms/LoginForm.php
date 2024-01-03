<?php 

class Default_Form_LoginForm extends Zend_Form{
    public function __construct($option=null) {
        parent::__construct($option);
        $this->setName('login');
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Usuário:')->setRequired(true);
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Senha:')->setRequired(true);

        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login')->setRequired(true);

        $this->addElements(array( 
            $username, $password, $login
        ));
        $this->setMethod('post');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/authentication/login');
    }
}

?>