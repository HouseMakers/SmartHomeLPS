<?php

class Alerts extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=8, nullable=false)
     */
    public $date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $alert_template_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smarthome");
        
        $this->hasOne("alert_template_id", "AlertsTemplate", "id", [
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
        return 'alerts';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Alerts[]|Alerts
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Alerts
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
