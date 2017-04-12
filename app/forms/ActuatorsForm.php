<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class ActuatorsForm extends Form
{

    /**
     * Initialize the actuator form
     */
    public function initialize($entity = null, $options = array())
    {
        if (!isset($options['edit'])) {
            $element = new Text("id");
            $this->add($element->setLabel("id"));
        } else {
            $this->add(new Hidden("id"));
        }
        
        $name = new Text("name");
        $name->setLabel("Nome");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Nome é obrigatório'
            ))
        ));
        $this->add($name);
        
        $description = new Text("description");
        $description->setLabel("Description");
        $description->setFilters(array('striptags', 'string'));
        $this->add($description);
    }
}