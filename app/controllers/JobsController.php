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

    public function addJob()
    {
        $this->view->assign('page_title', "Add Job");
        Config::setJsConfig('curPage', "add-job");
        $coming_soon = $this->view->render(Config::get('VIEWS_PATH') . 'dashboard/comingsoon.php',[

        ]);
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/jobs/", Config::get('VIEWS_PATH') . 'jobs/addJob.php',
            [
                'page_title'    => "Add Job",
                'coming_soon'   => $coming_soon
            ]);
    }

    public function jobSearch()
    {
        $this->view->assign('page_title', "Job Search");
        Config::setJsConfig('curPage', "job-search");
        $coming_soon = $this->view->render(Config::get('VIEWS_PATH') . 'dashboard/comingsoon.php',[

        ]);
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/jobs/", Config::get('VIEWS_PATH') . 'jobs/jobsSearch.php',
            [
                'page_title'    => "Job Search",
                'coming_soon'   => $coming_soon
            ]);
    }

    public function jobImporting()
    {
        $this->view->assign('page_title', "Job Importing");
        Config::setJsConfig('curPage', "job-importing");
        $coming_soon = $this->view->render(Config::get('VIEWS_PATH') . 'dashboard/comingsoon.php',[

        ]);
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/jobs/", Config::get('VIEWS_PATH') . 'jobs/jobImporting.php',
            [
                'page_title'    => "Import Jobs",
                'coming_soon'   => $coming_soon
            ]);
    }

    public function viewJobs()
    {
        //echo "<pre>",print_r($this->request->params['args']),"</pre>";die();
        $client_name = "All Clients";
        $courier_id = -1;
        $client_id = 0;
        $fulfilled = 0;
        $state = "";
        $ff = "Open";
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
                $ff = "Finalised";
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
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/jobs/", Config::get('VIEWS_PATH') . 'jobs/viewJobs.php', [
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
