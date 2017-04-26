<?php

use Phalcon\Http\Response;
use Phalcon\Http\Request;

class DevicesController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->section_title = "Dispositivos";
    }
    
    /**
     * Creates a new device
     */
    public function createAction()
    {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");
        
        $form = new DevicesForm;
        $device = new Devices();
        
        $data = $this->request->getPost();
        if ($form->isValid($data, $device)) {
            if ($device->save()) {
                $response->setStatusCode(200, "Ok");
                $response->setJsonContent(
                    array(
                        "device" => array(
                            "id" => $device->id,
                            "name" => $device->name,
                            "description" => $device->description,
                        )
                    )
                );
            }
            else {
                $response->setStatusCode(409, "Conflict");
                
                $messages = array();
                foreach ($device->getMessages() as $message) {
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
        
        $device = Devices::findFirst($id);
        if (empty($device)) {
            $response->setStatusCode(404, "Not Found");    
            $response->setJsonContent(
                array(
                    "error" => array(
                        "code"    => 404,
                        "message" => "Não foi possível encontrar o dispositivo informado",
                        "title"   => "Not Found"
                    )
                )
            );
        }
        else {
            if ($device->delete()) {
                $response->setStatusCode(200, "Ok");
                $response->setJsonContent(
                    array(
                        "device" => array(
                            "id" => $device->id,
                            "name" => $device->name,
                            "description" => $device->description
                        )
                    )
                );
            }
            else {
                $messages = array();
                foreach ($device->getMessages() as $message) {
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
        $query = Devices::query();
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
        
        $iTotalRecords = Devices::count();
        $iTotalDisplayRecords = Devices::count(array("conditions" => $where));
        
        $json = array(
            "sEcho" => $_GET['sEcho'],
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => array()
        );
        
        
        foreach ($data as $device) {
            $row = array();
            
            $row['id'] = $device->id;
            $row['name'] = $device->name;
            $row['status'] = "OFF";
            
            $json['aaData'][] = $row;
        }
        
        echo json_encode($json);
    }
}