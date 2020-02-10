<?php

/**
 * Site Settings controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class sitesettingsController extends Controller
{
    public function beforeAction()
    {
        parent::beforeAction();
    }

    public function locations()
    {
        $locations = $this->location->getAllLocations();
        //render the page
        Config::setJsConfig('curPage', "locations");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/locations.php',
        [
            'page_title'    =>  'Manage Locations',
            'locations'     =>  $locations
        ]);
    }

    public function pickfaces()
    {
        $pickfaces = $this->pickface->getAllPickfaces();
        //render the page
        Config::setJsConfig('curPage', "pickfaces");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/pickfaces.php',
        [
            'page_title'    =>  'Manage Pickfaces',
            'pickfaces'     =>  $pickfaces
        ]);
    }

    public function manageUsers()
    {
        $client_users = $this->user->getAllUsers('client');
        $admin_users = $this->user->getAllUsers('admin');
        $user_roles = $this->user->getUserRoles();
        $active = (isset($this->request->params['args']['active']))? $this->request->params['args']['active'] : -1;
        //render the page
        Config::setJsConfig('curPage', "manage-users");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/manageUsers.php',
        [
            'page_title'    =>  'Manage Users',
            'user_roles'    =>  $user_roles,
            'active'        =>  $active
        ]);
    }

    public function jobStatus()
    {
        //render the page
        Config::setJsConfig('curPage', "job-status");
        $statusses = $this->jobstatus->getAllStatuses();
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/jobStatus.php',
        [
            'page_title'    =>  'Manage Job Status Labels',
            'statusses'     =>  $statusses
        ]);
    }

    public function stockMovementReasons()
    {
        //render the page
        $reasons = $this->stockmovementlabels->getMovementLabels();
        Config::setJsConfig('curPage', "stock-movement-reasons");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/stockMovementReasons.php',
        [
            'page_title'    =>  'Manage Stock Movement Reasons',
            'reasons'        =>  $reasons
        ]);
    }

    public function couriers()
    {
        $couriers = $this->courier->getCouriers();
        //render the page
        Config::setJsConfig('curPage', "couriers");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/couriers.php',[
            'page_title'    =>  'Manage Couriers',
            'couriers'      =>  $couriers
        ]);
    }

    public function storeChains()
    {
        $chains = $this->storechain->getChains();
        //render the page
        Config::setJsConfig('curPage', "store-chains");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/storeChains.php',[
            'page_title'    =>  'Manage Store Chains',
            'chains'      =>  $chains
        ]);
    }

    public function userRoles()
    {
        $roles = $this->user->getUserRoles();
        //render the page
        Config::setJsConfig('curPage', "user-roles");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/sitesettings/", Config::get('VIEWS_PATH') . 'sitesettings/userRoles.php',[
            'page_title'  =>  'Manage User Roles',
            'roles'       =>  $roles
        ]);
    }

    public function isAuthorized(){
        $role = Session::getUserRole();
        $action = $this->request->param('action');
        $resource = "sitesettings";
        // only for super admins
        Permission::allow('super admin', $resource, ['*']);
        // all other admins
        Permission::allow(['admin'], $resource, [
            'jobStatus',
            'locations',
            'manageUsers',
            'orderStatus',
            'packingType',
            'staff',
            'stockMovementReasons'
        ]);

        //echo "<pre>",print_r(Permission::$perms),"</pre>"; die();
        return Permission::check($role, $resource, $action);
    }
}
?>