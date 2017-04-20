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
                "id" => $space->id,
                "type" => "Space"
            ]
        ]);
        
        return $response->getStatusCode() == 201;
    }
}