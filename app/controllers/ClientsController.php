<?php

/**
 * Clients controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class ClientsController extends Controller
{
    public function beforeAction()
    {
        parent::beforeAction();
        //set javascript to allocate active class to menu items
    }

    public function viewClients()
    {
        $active = (isset($this->request->params['args']['active']))? $this->request->params['args']['active'] : 1;

        $clients = $this->client->getAllClients($active);
        Config::setJsConfig('curPage', "view-clients");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/clients/", Config::get('VIEWS_PATH') . 'clients/viewClients.php',
            [
                'active'        =>  $active,
                'clients'       =>  $clients,
                'page_title'    =>  "View Clients"
            ]);
    }

    public function addClient()
    {
        $this->view->assign('page_title', "Add Client");
        Config::setJsConfig('curPage', "add-client");
        $coming_soon = $this->view->render(Config::get('VIEWS_PATH') . 'dashboard/comingsoon.php',[

        ]);
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/clients/", Config::get('VIEWS_PATH') . 'clients/addClient.php',
            [
                'page_title'    => "Add Client",
                'coming_soon'   => $coming_soon
            ]);
    }

    public function editClient()
    {
        //echo "<pre>",print_r($this->request->params['args']),"</pre>";die();
        //$client_id = $this->request->params['args'][0];
        $client_id = $this->request->params['args']['client'];
        $client_info = $this->client->getClientInfo($client_id);
        //render the page
        Config::setJsConfig('curPage', "edit-client");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/clients/", Config::get('VIEWS_PATH') . 'clients/editClient.php',
            [
                'client'        =>  $client_info,
                'page_title'    =>  "Edit Client"
            ]);
    }

    public function isAuthorized(){
        $role = (Session::isAdminUser())? 'admin' : Session::getUserRole();
        if( isset($role) && ($role === "admin"  || $role === "super admin") )
        {
            return true;
        }
        return false;
    }
}
?>