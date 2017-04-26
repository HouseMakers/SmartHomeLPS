<?php

use Phalcon\Http\Response;
use Phalcon\Http\Request;

class AlertstemplateController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->section_title = "Alertas";
    }
    
    /**
     * Creates a new actuator
     */
    public function createAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $alertTemplate = new AlertsTemplate();
        
        $data = $this->request->getPost();
        
        $alertTemplate->title = $data['title'];
        $alertTemplate->description = $data['description'];
        $alertTemplate->message = $data['message'];
        $alertTemplate->space_id = $data['space'];
        $alertTemplate->sensor = $data['sensor'];
        $alertTemplate->condition = $data['condition'];
        $alertTemplate->value = $data['value'];
        $alertTemplate->enable();
        
        if ($alertTemplate->save()) {
            $response->setStatusCode(200, "Ok");
            $response->setJsonContent(
                array(
                    "alert" => array(
                        "id" => $alertTemplate->id,
                        "title" => $alertTemplate->title,
                        "description" => $alertTemplate->description,
                        "message" => $alertTemplate->message,
                        "space" => $alertTemplate->space->name,
                        "sensor" => $alertTemplate->sensor,
                        "condition" => $alertTemplate->condition,
                        "value" => $alertTemplate->value,
                        "status" => $alertTemplate->status
                    )
                )
            );
        }
        else {
            $response->setStatusCode(409, "Conflict");
            
            $messages = array();
            foreach ($alertTemplate->getMessages() as $message) {
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
        
        $response->send();
    }
    
    public function deleteAction($id)
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $alertTemplate = AlertsTemplate::findFirst($id);
        if (empty($alertTemplate)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Não foi possível encontrar o alerta informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            if ($alertTemplate->delete()) {
                $response->setStatusCode(200, "Ok");
                $response->setJsonContent(
                    array(
                        "alert" => array(
                            "id" => $alertTemplate->id,
                            "title" => $alertTemplate->title,
                            "description" => $alertTemplate->description,
                            "message" => $alertTemplate->message,
                            "space" => $alertTemplate->space->name,
                            "sensor" => $alertTemplate->sensor,
                            "condition" => $alertTemplate->condition,
                            "value" => $alertTemplate->value,
                            "status" => $alertTemplate->status
                        )
                    )
                );
            }
            else {
                $messages = array();
                foreach ($alertTemplate->getMessages() as $message) {
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
        
        $columns = array('id', 'title', 'sensor', 'status');
        $query = AlertsTemplate::query();
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
        
        $iTotalRecords = AlertsTemplate::count();
        $iTotalDisplayRecords = AlertsTemplate::count(array("conditions" => $where));
        
        $json = array(
            "sEcho" => $_GET['sEcho'],
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => array()
        );
        
        
        foreach ($data as $alertTemplate) {
            $row = array();
            
            $alertTemplate = AlertsTemplate::findFirst($alertTemplate['id']);
            
            $row['id'] = $alertTemplate->id;
            $row['title'] = $alertTemplate->title;
            $row['space'] = $alertTemplate->space->name;
            $row['sensor'] = $this->t->_($alertTemplate->sensor);
            $row['status'] = $alertTemplate->status;
            
            $json['aaData'][] = $row;
        }
        
        echo json_encode($json);
    }
    
    public function changeStatusAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $data = $this->request->getPost();
        
        $alertTemplate = AlertsTemplate::findFirst($data['id']);
        
        if($data['status'] == "true") {
            $alertTemplate->enable(); 
        }
        else {
            $alertTemplate->disable(); 
        }
        
        if($alertTemplate->save()) {
            $response->setStatusCode(200, "Ok");
        }
        else {
            $response->setStatusCode(409, "Conflict");
            
            $messages = array();
            foreach ($alertTemplate->getMessages() as $message) {
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
        
        $response->send();
    }
}