<?php

/**
 * Jobs controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class JobsController extends Controller{

    public function beforeAction(){
        parent::beforeAction();
        $this->Security->config("form", [ 'fields' => ['csrf_token']]);
    }

    public function viewJobs()
    {
        //echo "<pre>",print_r($this->request->params['args']),"</pre>";die();
        $client_name = "All Clients";
        $courier_id = -1;
        $client_id = 0;
        $fulfilled = 0;
        $state = "";
        $ff = "Unfulfilled";
        if(!empty($this->request->params['args']))
        {
            if(isset($this->request->params['args']['client']))
            {
                $client_id = $this->request->params['args']['client'];
                $client_name = $this->client->getClientName($client_id);
            }
            if(isset($this->request->params['args']['courier']))
            {
                $courier_id = $this->request->params['args']['courier'];
            }
            if(isset($this->request->params['args']['fulfilled']))
            {
                $fulfilled = $this->request->params['args']['fulfilled'];
                $ff = "Fulfilled";
            }
            if(isset($this->request->params['args']['state']))
            {
                $state = $this->request->params['args']['state'];
            }
        }
        $page_title = "$ff Jobs For $client_name";
        $jobs = $this->job->getAllJobs($client_id, $courier_id, $fulfilled, 0, $state);
        //render the page
        Config::setJsConfig('curPage', "view-jobs");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/orders/", Config::get('VIEWS_PATH') . 'orders/viewOrders.php', [
            'page_title'    =>  $page_title,
            'client_name'   =>  $client_name,
            'client_id'     =>  $client_id,
            'courier_id'    =>  $courier_id,
            'jobs'          =>  $jobs,
            'fulfilled'     =>  $fulfilled,
            'state'         =>  $state
        ]);
    }


    public function isAuthorized(){
        $action = $this->request->param('action');
        $role = (Session::isAdminUser())? 'admin' : Session::getUserRole();
        $resource = "orders";
        //only for admin
        Permission::allow('admin', $resource, "*");
        Permission::allow('super admin', $resource, "*");
        //warehouse users
        Permission::allow('warehouse', $resource, array(

        ));
        //client users
        $allowed_resources = array(

        );
        Permission::allow('client', $resource, $allowed_resources);
        return Permission::check($role, $resource, $action);
    }
}
