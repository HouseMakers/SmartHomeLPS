<?php

class Devices extends \Phalcon\Mvc\Model
{
    const DEVICE = 'Device';
    const SENSOR = 'Sensor';

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;
    
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $id_space;
    
    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $category;
    
    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $type;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $name;
    
    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $status;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $description;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smarthome");
        
        $this->belongsTo("id_space", "Spaces", "id", [
            'alias' => 'space'
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'devices';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actuators[]|Actuators
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actuators
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
