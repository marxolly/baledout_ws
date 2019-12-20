<?php

/**
 * Dashboard controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class DashboardController extends Controller
{
    /**
     * show dashboard page
     *
     */
    public function index()
    {
        $orders = array();
        $client_id = 0;
        $clients = array();
        $user_role = (Session::isAdminUser())? 'admin' : Session::getUserRole();;
        $variables = array(
            'client_id'             =>  $client_id,
            'orders'                =>  $orders,
            'clients'               =>  $clients,
            'user_role'             =>  $user_role
        );
        echo "<pre>",print_r($variables),"</pre>";die();
        Config::setJsConfig('curPage', "dashboard");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/dashboard/", Config::get('VIEWS_PATH') . 'dashboard/index.php',[
            'client_id'             =>  $client_id,
            'orders'                =>  $orders,
            'clients'               =>  $clients,
            'solar_service_jobs'    =>  $solar_service_jobs,
            'store_orders'          =>  $store_orders,
            'solar_orders'          =>  $solar_installs,
            'user_role'             =>  $user_role
        ]);
    }

    public function isAuthorized(){
        return true;
    }
}