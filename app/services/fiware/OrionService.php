<?php

//namespace SmartHomeLPS\Services\Fiware;
    
use Phalcon\Mvc\User\Component;

class OrionService extends Component
{
    public function registerSpace($space) 
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "entities", [
                'json' => [
                    "id" => $space->id,
                    "type" => "Space"
                ]
            ]);
        } catch(\Exception $e) {
            error_log("Erro to create entity");
        }
        
        return $response->getStatusCode() == 201;
    }
    
    public function deleteSpace($id)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->request('DELETE', $this->config->fiware->orionBaseUrl . "entities/" . $id);
        }
        catch(\Exception $e) {
            error_log("Erro to delete entity");
        }
        
        return $response->getStatusCode() == 201;
    }
    
    public function addSensorsToSpace($space, $sensors)
    {
        $entity = array(
            "id" => $space->id,
            "type" => "Space"
        );
        
        $attributes = array();
        foreach($sensors as $sensor) {
            $configSensor = $this->searchInConfig($sensor);
            $entity[$configSensor['name']] = ['type' => $configSensor['type']];
            array_push($attributes, $configSensor['name']);
        }
        
        $client = new \GuzzleHttp\Client();
        
        try {
            $client->request('DELETE', $this->config->fiware->orionBaseUrl . "entities/" . $space->id);
        } catch(\Exception $e) {
            error_log("Erro to delete entity");
        }

        try {
            $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "entities", [
                'json' => $entity
            ]);
        } catch(\Exception $e) {
            error_log("Erro to create entity");
        }
        
        if ($response->getStatusCode() == 201) {
            $response = $this->subscribe($space, $attributes);
        }
        
        return $response->getStatusCode() == 200 || $response->getStatusCode() == 201;
    }
    
    private function subscribe($space, $attributes)
    {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('GET', $this->config->fiware->orionBaseUrl . "subscriptions");
        
        $subscriptions = json_decode($response->getBody());
        
        $subscription = null;
        for($i = 0; $i < count($subscriptions); $i++) {
            $subscriptionsEntities = $subscriptions[$i]->subject->entities;
            if ($subscriptionsEntities[0]->id == ($space->id)) {
                $client->request('DELETE', $this->config->fiware->orionBaseUrl . "subscriptions/" . $subscriptions[$i]->id);
            }
        }
        
        foreach($attributes as $attribute) {
            $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "subscriptions", [
                'json' => [
                    "subject" => [
                        "entities" => [
                            [
                                "id" => $space->id,
                                "type" => "Space"
                            ]
                        ],
                        "condition" => [
                            "attrs" => [$attribute]
                        ]
                    ],
                    "notification" => [
                        "http" => [
                          "url" => $this->config->application->baseUrl . "brunno/testando/notify.php"
                        ],
                        "attrs" => [$attribute]
                    ],
                    "throttling" => 3
                ]
            ]);
        }
        
        return $response;
    }
    
    private function searchInConfig($sensor)
    {
        $configSensors = $this->config->get("smarthome")->get("sensors");
        
        foreach($configSensors as $configSensor) {
            if ($sensor->type === $configSensor->name) {
                return $configSensor;
            }
        }
        
        return null;
    }
}