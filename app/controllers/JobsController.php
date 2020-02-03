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




    public function isAuthorized(){
        $action = $this->request->param('action');
        //$role = Session::getUserRole();
        $role = (Session::isAdminUser())? 'admin' : Session::getUserRole();
        $resource = "orders";

        //only for admin
        Permission::allow('admin', $resource, "*");
        Permission::allow('super admin', $resource, "*");
        //warhouse users
        Permission::allow('warehouse', $resource, array(
            "orderDispatching",
            "orderPacking",
            "orderPicking",
            "orderSearch",
            "orderSearchResults",
            "viewOrders",
            "orderUpdate",
            "addressUpdate",
            "orderEdit",
            "viewDetails",
            "viewStoreorders"
        ));
        //only for clients
        $allowed_resources = array(
            "addOrder",
            "addOrderTest",
            "bookPickup",
            "bulkUploadOrders",
            "clientOrders",
            "orderTracking",
            "orderDetail",
        );
        Permission::allow('client', $resource, $allowed_resources);
        return Permission::check($role, $resource, $action);
    }
}
