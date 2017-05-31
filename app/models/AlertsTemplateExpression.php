<?php

class AlertsTemplateExpression extends \Phalcon\Mvc\Model
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
    public $alert_template_id;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $condition;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smarthome");
        
        $this->belongsTo("alert_template_id", "AlertsTemplate", "id", [
            'alias' => 'template'
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'alerts_template_expression';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AlertsTemplateExpression[]|AlertsTemplateExpression
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AlertsTemplateExpression
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
