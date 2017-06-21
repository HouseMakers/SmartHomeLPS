<?php

namespace SmartHomeLPS\Services\SmartHome;
    
use Phalcon\Mvc\User\Component;

class CentralService extends Component
{
    public function act($device, $action, $parameters = []) {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->request(
                'GET', $this->config->smarthome->central->baseUrl . "act/" . $device->id . "/" . strtolower($action) . "/1"
            );
        } catch(\Exception $e) {
            error_log("Erro to actuator in device: " . $e->getMessage());
        }
        
        return $response->getStatusCode() == 200;
    }
}