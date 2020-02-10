<?php

/**
 * Staff controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class staffController extends Controller
{
    public function beforeAction()
    {
        parent::beforeAction();
    }

    public function viewStaff()
    {
        //render the page
        Config::setJsConfig('curPage', "view-staff");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/staff/", Config::get('VIEWS_PATH') . 'staff/viewStaff.php',
        [
            'page_title'    =>  'Manage Staff Records'
        ]);
    }

    public function isAuthorized(){
        $role = Session::getUserRole();
        $action = $this->request->param('action');
        $resource = "staff";
        // only for super admins
        Permission::allow('super admin', $resource, ['*']);
        // all other admins
        Permission::allow(['admin'], $resource, ['*']);

        //echo "<pre>",print_r(Permission::$perms),"</pre>"; die();
        return Permission::check($role, $resource, $action);
    }
}
?>