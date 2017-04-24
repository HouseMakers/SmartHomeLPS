<?php

use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;

use SmartHomeLPS\Services\Fiware\OrionService;

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
                        "message" => "Não foi possível encontrar o atuador informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $sensors = $space->sensors;
            if ($space->delete()) {
                foreach($sensors as $sensor){
                    $sensor->id_space = NULL;
                    $sensor->save();
                }
                
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
    
    public function sensorsAction($id)
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
                        "message" => "Não foi possível encontrar o sensor informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            $mappedSensors = $space->sensors->toArray();
            $allSensors = Sensors::find("id_space is NULL")->toArray();
            
            for($i = 0; $i < count($allSensors); $i++) {
                $allSensors[$i]['type'] = $this->t->_($allSensors[$i]['type']);
            }
            
            $availableSensors = array_udiff($allSensors, $mappedSensors, function($x, $y) {
                return strcmp($x['id'], $y['id']);
            });
            
            $availableSensorsArray = array();
            foreach($availableSensors as $availableSensor) {
                array_push($availableSensorsArray, $availableSensor);
            }
            
            $response->setStatusCode(200, "Ok");
            $response->setJsonContent(
                array(
                    "mapped_sensors" => $mappedSensors,
                    "available_sensors" => $availableSensorsArray
                )
            );
        }
        
        $response->send();
    }
    
    public function saveSensorsAction()
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
            $idSensors = isset($data["sensors"]) ? $data["sensors"] : [];
            
            $sensors = $space->sensors;
            foreach($sensors as $sensor) {
                $sensor->id_space = null;
                $sensor->save();
            }
            
            $sensors = array();
            foreach($idSensors as $idSensor) {
                $sensor =  Sensors::findFirst($idSensor);
                $sensor->space = $space;
                $sensor->save();
                
                array_push($sensors, $sensor);
            }
            
            $orionService = new OrionService();
            if($orionService->addSensorsToSpace($space,$sensors)){
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
}