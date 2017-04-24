<?php

namespace SmartHomeLPS\Services\Fiware;
    
use Phalcon\Mvc\User\Component;

class OrionService extends Component
{
    public function registerSpace($space) 
    {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "entities", [
            'json' => [
                "id" => "Space" . $space->id,
                "type" => "Space"
            ]
        ]);
        
        return $response->getStatusCode() == 201;
    }
    
    public function addSensorsToSpace($space, $sensors)
    {
        $entity = array(
            "id" => "Space" . $space->id,
            "type" => "Space"
        );
        
        $attributes = array();
        foreach($sensors as $sensor) {
            $configSensor = $this->searchInConfig($sensor);
            $entity[$configSensor['name']] = ['type' => $configSensor['type']];
            array_push($attributes, $configSensor['name']);
        }
        
        $client = new \GuzzleHttp\Client();
        
        $client->request('DELETE', $this->config->fiware->orionBaseUrl . "entities/Space" . $space->id);
        
        $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "entities", [
            'json' => $entity
        ]);
        
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
        for($i = 0; $i < count($subscriptions) and $subscription == null; $i++) {
            $subscriptionsEntities = $subscriptions[$i]->subject->entities;
            if ($subscriptionsEntities[0]->id == ("Space" . $space->id)) {
                $subscription = $subscriptions[$i];
            }
        }
        
        if ($subscription != null) {
            $client->request('DELETE', $this->config->fiware->orionBaseUrl . "subscriptions/" . $subscription->id);
        }
        
        $response = $client->request('POST', $this->config->fiware->orionBaseUrl . "subscriptions", [
            'json' => [
                "subject" => [
                    "entities" => [
                        [
                            "id" => "Space" . $space->id,
                            "type" => "Space"
                        ]
                    ],
                    "condition" => [
                        "attrs" => $attributes
                    ]
                ],
                "notification" => [
                    "http" => [
                      "url" => $this->config->application->baseUrl . "brunno/testando/notify.php"
                    ],
                    "attrs" => $attributes
                ],
                "throttling" => 3
            ]
        ]);
        
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