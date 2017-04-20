<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class SensorsForm extends Form
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
        
        $sensors = array();
        for($i = 0; $i < count($this->config->smarthome->sensors); $i++) {
            $sensors[$this->config->smarthome->sensors[$i]['name']] = $this->config->smarthome->sensors[$i]['translation']['pt-br'];
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
        
        $type = new Select("type", $sensors, array(
                'class' => 'form-control',
                'placeholder' => "O nome do sensor",
            )
        );
        $type->setLabel("Tipo");
        $type->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tipo é obrigatório'
            ))
        ));
        $this->add($type);
        
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