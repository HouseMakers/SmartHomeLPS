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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $space_id;
    
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $device_id;

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
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smarthome");
        
        $this->belongsTo("space_id", "Spaces", "id", [
            'alias' => 'space'
        ]);
        
        $this->belongsTo("device_id", "Devices", "id", [
            'alias' => 'device'
        ]);
        
        $this->hasOne("id", "AlertsTemplateExpression", "alert_template_id", [
            'alias' => 'expression'
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
