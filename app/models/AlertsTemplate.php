<?php

class AlertsTemplate extends \Phalcon\Mvc\Model
{

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
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $message;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $sensor;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $condition;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $value;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $space_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smarthome");
        
        $this->hasOne("space_id", "Spaces", "id", [
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
        return 'alerts_template';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AlertsTemplate[]|AlertsTemplate
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AlertsTemplate
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
    public function isActive()
    {
        return $this->status == "ON";
    }
    
    public function enable()
    {
        $this->status = "ON";
    }
    
    public function disable()
    {
        $this->status = "OFF";
    }
    
    public function getStatusDescription()
    {
        $description = "Ligado";
        
        if($this->status == "OFF") {
            $description = "Desligado";
        }
        
        return $description;
    }
}
