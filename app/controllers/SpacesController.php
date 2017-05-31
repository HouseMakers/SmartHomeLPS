<?php

use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;

//use SmartHomeLPS\Services\Fiware\OrionService;

require_once(__DIR__ . "/../services/fiware/OrionService.php");

class SpacesController extends ControllerBase
{
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {  
        parent::beforeExecuteRoute($dispatcher);
        
        $this->view->section_title = "Espaços";
    }
    
    public function indexAction()
    {
        
    }
    
    /**
     * Creates a new spaces
     */
    public function createAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $form = new SpacesForm;
        $space = new Spaces();
        
        $data = $this->request->getPost();
        if ($form->isValid($data, $space)) {
            $this->db->begin();
            
            if ($space->save()) {
                $orionService = new OrionService();
                
                if($orionService->registerSpace($space)) {
                    $this->db->commit();
                    
                    $response->setStatusCode(200, "Ok");
                    $response->setJsonContent(
                        array(
                            "space" => array(
                                "id" => $space->id,
                                "name" => $space->name
                            )
                        )
                    );
                }
                else {
                    $this->db->rollback();
                    
                    $response->setStatusCode(409, "Conflict");
                    $response->setJsonContent(
                        array(
                            "error" => array(
                                "code"   => 409,
                                "message" => $messages,
                                "title" => "Conflict"
                            )
                        )
                    );
                }
                
            }
            else {
                $response->setStatusCode(409, "Conflict");
                
                $messages = array();
                foreach ($space->getMessages() as $message) {
                    array_push($messages, $message->getMessage());
                }
                
                $response->setJsonContent(
                    array(
                        "error" => array(
                            "code"   => 409,
                            "message" => $messages,
                            "title" => "Conflict"
                        )
                    )
                );
            }
        }
        else {
            $response->setStatusCode(400, "Bad Request");
            
            $messages = array();
            foreach ($form->getMessages() as $message) {
                array_push($messages, $message->getMessage());
            }
            
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"   => 400,
                        "message" => $messages,
                        "title" => "Bad Request"
                    )
                )
            );
        }
        
        $response->send();
    }
    
    public function deleteAction($id)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $space = Spaces::findFirst($id);
        if (empty($space)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Não foi possível encontrar o espaço informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $devices = $space->devices;
            if ($devices->delete()) {
                foreach($devices as $device){
                    $device->id_space = NULL;
                    $device->save();
                }
                
                $orionService = new OrionService();
                $orionService->deleteSpace($space->id);
                
                $response->setStatusCode(200, "Ok");
                $response->setJsonContent(
                    array(
                        "space" => array(
                            "id" => $space->id,
                            "name" => $space->name
                        )
                    )
                );
            }
            else {
                $messages = array();
                foreach ($space->getMessages() as $message) {
                    array_push($messages, $message->getMessage());
                }
                
                $response->setStatusCode(409, "Conflict");
                $response->setJsonContent(
                    array(
                        "error" => array(
                            "code"   => 409,
                            "message" => $messages,
                            "title" => "Conflict"
                        )
                    )
                );
            }
        }
        
        $response->send();
    }
    
    public function searchAction()
    {
        $this->view->disable();
        
        $columns = array('id', 'name');
        $query = Spaces::query();
        $query->columns($columns);
        
        $where = "";
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
            $filter = new \Phalcon\Filter();
            $search = $filter->sanitize($_GET['sSearch'], "string");
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                $where .= $columns[$i] . " LIKE '%". $search ."%' OR ";
            }
            $where = substr_replace( $where, "", -3 );
        }

        $order = "";
        if ( isset( $_GET['iSortCol_0'] ) )
        {
            for ( $i=0 ; $i< $_GET['iSortingCols']; $i++ )
            {
                if ( $_GET[ 'bSortable_' . $_GET['iSortCol_'.$i] ] == "true" )
                {
                    $order .= $columns[ $_GET['iSortCol_'.$i] ] . " " . $_GET['sSortDir_'.$i] .", ";
                }
            }
            $order = substr_replace( $order, "", -2 );
        }
        
        $limit = array("", "");
        if ( isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != -1 )
        {
            $limit = array($_GET['iDisplayLength'], $_GET['iDisplayStart']);
        }
        
        if (empty($where)) {
            $where = "true";
        }
        
        $query->where($where);
        $query->orderBy($order);
        $query->limit($limit[0], $limit[1]);
        $data = $query->execute();
        
        $iTotalRecords = Spaces::count();
        $iTotalDisplayRecords = Spaces::count(array("conditions" => $where));
        
        $json = array(
            "sEcho" => $_GET['sEcho'],
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => array()
        );
        
        
        foreach ($data as $space) {
            $row = array();
            
            $row['id'] = $space->id;
            $row['name'] = $space->name;
            
            $json['aaData'][] = $row;
        }
        
        echo json_encode($json);
    }
    
    public function devicesAction($id)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $space = Spaces::findFirst($id);
        if (empty($space)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Não foi possível encontrar o espaço informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $mappedDevices = $space->devices->toArray();
            $allDevices = Devices::find("id_space is NULL")->toArray();
            
            for($i = 0; $i < count($allDevices); $i++) {
                $allDevices[$i]['type'] = $this->t->_($allDevices[$i]['type']);
            }
            
            $availableDevices = array_udiff($allDevices, $mappedDevices, function($x, $y) {
                return strcmp($x['id'], $y['id']);
            });
            
            $availableDevicesArray = array();
            foreach($availableDevices as $availableDevice) {
                array_push($availableDevicesArray, $availableDevice);
            }
            
            $response->setStatusCode(200, "Ok");
            $response->setJsonContent(
                array(
                    "mapped_devices" => $mappedDevices,
                    "available_devices" => $availableDevicesArray
                )
            );
        }
        
        $response->send();
    }
    
    public function saveDevicesAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $data = $this->request->getPost();
        
        $space = Spaces::findFirst($data["id"]);
        if (empty($space)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Não foi possível encontrar o espaço informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $idDevices = isset($data["devices"]) ? $data["devices"] : [];
            
            $devices = $space->devices;
            foreach($devices as $device) {
                $device->id_space = null;
                $device->save();
            }
            
            $sensors = array();
            foreach($idDevices as $idDevice) {
                $device =  Devices::findFirst($idDevice);
                $device->space = $space;
                $device->save();
                
                if ($device->category == Devices::SENSOR) {
                    array_push($sensors, $device);
                }
            }
            
            $orionService = new OrionService();
            if($orionService->addSensorsToSpace($space, $sensors)){
                $response->setStatusCode(200, "Ok");
                $response->setJsonContent(
                    array(
                        "space" => array(
                            "id" => $space->id,
                            "name" => $space->name
                        )
                    )
                );
            }
            else {
                $response->setStatusCode(400, "Conflict");    
                $response->setJsonContent(
                    array(
                        "error" => array(
                            "code"    => 400,
                            "message" => "Erro ao savar sensores",
                            "title"   => "Not Found"
                        )
                    )
                );
            }
        }
        
        $response->send();
    }
    
    public function listAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $spaces = Spaces::find();
        if (empty($spaces)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Nenhum espaço encontrado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $response->setStatusCode(200, "Ok");
            $response->setJsonContent(
                array(
                    "spaces" => $spaces->toArray()
                )
            );
        }
        
        $response->send();
    }
    
    public function featuresAction($id, $category)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $space = Spaces::findFirst($id);
        $devices = $space->devices;
        
        $features = array();
        foreach($devices as $device) {
            if ($device->category == $category) {
                $configSensor = $this->searchInConfig($device);
                
                if (empty($configSensor)) {
                    $configSensor = ['type' => 'String'];
                }
                
                array_push($features, [
                    'id' => $device->id, 
                    'name' => $device->name, 
                    'type' => $this->t->_($device->type),
                    'data-type' => $configSensor['type']
                ]);
            }
        }
        
        $response->setStatusCode(200, "Ok");
        $response->setJsonContent(
            array(
                "features" => $features
            )
        );
        
        $response->send();
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