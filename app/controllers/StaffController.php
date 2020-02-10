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
        die('viewing staff');
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