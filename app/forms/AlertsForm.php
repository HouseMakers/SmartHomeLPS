<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class AlertsForm extends Form
{

    /**
     * Initialize the actuator form
     */
    public function initialize($entity = null, $options = array())
    {
        if (!isset($options['edit'])) {
            $element = new Text("id", array('class' => 'form-control'));
            $this->add($element->setLabel("id"));
        } else {
            $this->add(new Hidden("id"));
        }
        
        $name = new Text("name", array(
                'class' => 'form-control',
                'placeholder' => "Nome",
            )
        );
        $name->setLabel("Name");
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Nome é obrigatório'
            ))
        ));
        $name->setFilters(array('striptags', 'string'));
        $this->add($name);
        
        $description = new TextArea("description", array(
                'class' => 'form-control',
                'placeholder' => "Descrição",
            )
        );
        $description->setLabel("Description");
        $description->setFilters(array('striptags', 'string'));
        $this->add($description);
    }
}