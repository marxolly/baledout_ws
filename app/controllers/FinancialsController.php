<?php

/**
 * Financials controller
 *

 * @author     Mark Solly <mark@baledout.com.au>
 */

class FinancialsController extends Controller
{
    public function beforeAction()
    {
        parent::beforeAction();
    }
    
    public function xeroInfo()
    {
        $org = $this->xero_auth->$org;

        Config::setJsConfig('curPage', "xero-info");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/financials/", Config::get('VIEWS_PATH') . 'financials/xero-info.php',[
            'org'        => $org,
            'page_title' => 'Xero Info'
        ]);
    }

    public function isAuthorized(){
        return true;
    }
}