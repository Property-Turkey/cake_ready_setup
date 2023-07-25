<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

ini_set('max_execution_time', '0'); // 0 for infinite time of

class ContentsController extends AppController
{
    
    public function index()
    {
        
        if ($this->request->is('post')) {

            $this->autoRender = false;

            $conditions = [ ];

            // Filters and Search
            $_from = !empty($_GET['from']) ? $_GET['from'] : '';
            $_to = !empty($_GET['to']) ? $_GET['to'] : '';

            $_method = !empty($_GET['method']) ? $_GET['method'] : '';
            $_col = !empty($_GET['col']) ? $_GET['col'] : 'id';
            $_k = (isset($_GET['k']) && strlen($_GET['k'])>0) ? $_GET['k'] : false;
            $_dir = !empty($_GET['direction']) ? $_GET['direction'] : 'DESC';
    
            
            if( !empty($_from) ){ $conditions['Contents.stat_created > '] = $_from; }
            if( !empty($_to) ){ $conditions['Contents.stat_created < '] = $_to; }
            if($_k !== false){
                $_method == 'like' ?  $conditions[$_col.' LIKE '] = '%'.$_k.'%' : $conditions['Contents.'.$_col] = $_k;
            }
            
            $data=[];
            $_id = $this->request->getQuery('id');
            $_list = $this->request->getQuery('list');

            // ONE RECORD
            if(!empty($_id)){
                $data  = $this->Contents->get( $_id, ['contain'=>['Specs']] );
            }

            // LIST
            if(!empty($_list)){ 
                $settings = ['contain'=>['Specs']];
                $data = $this->paginate( $this->Contents, $settings );
            }
            
            echo json_encode( 
                [ 
                    "status"=>"SUCCESS",  
                    "data"=>$this->Do->convertJson($data), 
                    "paging"=>!empty($_list) ? $this->request->getAttribute('paging')['Contents'] : null
                ], 
                JSON_UNESCAPED_UNICODE); die();
        }

    }

    public function save($id = -1, $requests_inc=0) 
    {
        $dt = json_decode( file_get_contents('php://input'), true);

        // edit mode
        if ($this->request->is(['patch', 'put'])) {
            $rec = $this->Contents->get($dt['id']);
        }

        // add mode
        if ($this->request->is(['post'])) {
            $rec = $this->Contents->newEmptyEntity();
            $dt['id'] = null;
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            
            $this->autoRender  = false;
            
            $dt['stat_updated'] = date('Y-m-d H:i:s');
            $rec = $this->Contents->patchEntity($rec, $dt);
            
            if ($newRec = $this->Contents->save($rec)) {
                echo json_encode(["status"=>"SUCCESS", "data"=>$newRec]); die();
            }

            echo json_encode(["status"=>"FAIL", "data"=>$rec->getErrors()]); die();
        }
    }

	public function delimage() 
    {
        $this->request->allowMethod(['delete']);
        $ctrl = $this->request->getParam('controller');
        $this->autoRender  = false;
        $dt = json_decode( file_get_contents('php://input'), true);
        $rec = $this->$ctrl->get($dt['id']);
        $langPrefix='';
        if($rec->language_id>0){
            $langPrefix = $this->Do->get('langs')[$rec->language_id].'/';
        }
		if( $this->Images->deleteFile('img/'.$langPrefix.strtolower( $ctrl ).'_photos', $dt['image'])){
            
			$imgsArray = explode(",", $rec->office_photos);
            $key = array_search($dt['image'], $imgsArray);
			unset($imgsArray[$key]);
			$update = ["id"=>$dt['id'], "office_photos"=>implode(",",$imgsArray)];
        	$updated_rec = $this->$ctrl->patchEntity($rec, $update);
			$saved = $this->$ctrl->save($updated_rec);
            echo json_encode(["status"=>"SUCCESS", "data"=>$saved]);  die();
		}else{
            echo json_encode(["status"=>"FAIL", "data"=>$dt]); die();
		}
	}

    public function delete($id = null)
    {
        if(!$id){
            echo json_encode( ["status"=>"FAIL", "msg"=>__("is-selected-empty-msg"), "data"=>[]] ); die();
        }
        $this->request->allowMethod(['post', 'delete']);
        $this->autoRender  = false;

        if(!$this->_isAuthorized(true)){
            echo json_encode( ["status"=>"FAIL", "msg"=>__("no-auth"), "data"=>[]] ); die();
        }

        $delRec=[];
        foreach(explode(",", $id) as $k=>$rec_id){
            $rec = $this->Contents->get($rec_id, [
                'contain'=>['Specs'=>['conditions'=>[
                    'OR'=>[['spec_name'=>'img'] , ['spec_name'=>'floorplan']] 
                ]]]
            ]);
            if($delRec[$k] = $this->Contents->delete($rec)){
                $langPrefix='';
                if($rec->language_id>0){
                    $langPrefix = $this->Do->get('langs')[$rec->language_id].'/';
                }
                $this->Images->deleteFile('img/'.$langPrefix.'contents_photos', array_values( array_column( $rec['specs'], 'spec_value')));
            }
        }
        
        $res = (!empty(array_filter($delRec))) ? ["status"=>"SUCCESS", "data"=>$delRec] : ["status"=>"FAIL", "data"=>$delRec];

        echo json_encode($res);die();

    }
    
    public function enable($val=1, $ids=null)
    {
        if(!$ids){
            echo json_encode( ["status"=>"FAIL", "msg"=>__("is-selected-empty-msg"), "data"=>[]] ); die();
        }
        $this->request->allowMethod(['post', 'delete']);
        $this->autoRender  = false;

        if(!$this->_isAuthorized(true)){
            echo json_encode( ["status"=>"FAIL", "msg"=>__("no-auth"), "data"=>[]] ); die();
        }

        $updateRec=[];
        foreach(explode(',', $ids) as $k=>$id){
            $rec = $this->Contents->newEmptyEntity();
            $rec['id'] = $id;
            $rec['rec_state'] = $val;
            $updateRec[$k] = $this->Contents->save($rec);
        }
        
        $res = (!empty(array_filter($updateRec))) ? ["status"=>"SUCCESS", "data"=>$updateRec] : ["status"=>"FAIL", "data"=>$updateRec];

        echo json_encode($res);die();
    }
    
    function beforeFilter(EventInterface $event) 
    {
        parent::beforeFilter($event);
        
        if ($this->request->is(['post', 'patch', 'put', 'delete'])) {
            if(!$this->_isAuthorized(true, 'read')){
                echo json_encode(["status" => "FAIL", "redirect" => $this->app_folder.'/?login=1']); die();
            }
        }
    }
}