<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 29/05/2017
 * Time: 08:50
 */

namespace SmartHomeLPS\Services\SmartHome;

require __DIR__.'/../../vendor/aws.phar';

use Aws\Sns\SnsClient;

class SMSAlertService extends AlertService
{
    public function alert($alert)
    {
        $params = array(
            'credentials' => array(
                'key' => $this->config->amazon->key,
                'secret' => $this->config->amazon->secret,
            ),
            'region' => 'us-west-2',
            'scheme' => 'http',
            'version' => 'latest'
        );
        $client = new SnsClient($params);

        $args = array(
            "SenderID" => "SmartHome",
            "SMSType" => "Transational",
            "Message" => $alert->template->message,
            "PhoneNumber" => "5584996637652"
        );

        $result = $client->publish($args);

        return $result->get('statusCode') == '200' ? true : false;
    }
}