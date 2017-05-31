<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;

class DevicesForm extends Form
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
        
        $devices = array();
        for($i = 0; $i < count($this->config->smarthome->devices); $i++) {
            $devices[$this->config->smarthome->devices[$i]['name']] = $this->t->_($this->config->smarthome->devices[$i]['name']);
        }
        
        $name = new Text("name", array(
                'class' => 'form-control',
                'placeholder' => "Nome",
            )
        );
        $name->setLabel("Name");
        $name->setFilters(array('striptags', 'string'));
        $name->setAttribute('required', 'true');
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Nome é obrigatório'
            ))
        ));
        $this->add($name);
        
        $type = new Select("type", $devices, array(
                'class' => 'form-control',
                'placeholder' => "O tipo do dispositivo",
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
        $description->setAttribute('required', 'true');
        $this->add($description);
    }
}