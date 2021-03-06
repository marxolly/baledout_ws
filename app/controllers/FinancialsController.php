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
        $org = $this->xero_auth->getOrganisation();
        $contacts = $this->xero_auth->getContacts();
        $invoices = $this->xero_auth->getInvoices();

        Config::setJsConfig('curPage', "xero-info");
        $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/financials/", Config::get('VIEWS_PATH') . 'financials/xero-info.php',[
            'org'           => $org,
            'contacts'      => $contacts,
            'invoices'      => $invoices,
            'page_title'    => 'Xero Info'
        ]);
    }

    public function isAuthorized(){
        return true;
    }
}