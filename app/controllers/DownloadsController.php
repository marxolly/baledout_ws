<?php

/**
 * Downloads controller
 *
 
 * @author     Mark Solly <mark@baledout.com.au>
 */
class DownloadsController extends Controller {


    public function beforeAction(){
        /*  */
        parent::beforeAction();
        $action = $this->request->param('action');
        $this->Security->config("validateForm", false);
        $post_actions = ['clientDispatchReportCSV'];
        $this->Security->requirePost($post_actions);
        $secure_downloads = [
            'stockMovementCSV',
            'clientBayUsageCSV',
            'clientDispatchReportCSV',
            'clientStockMovementCSV',
            'clientStockSummaryCSV',
            'dispatchReportCSV',
            'goodsInReportCSV',
            'goodsOutReportCSV',
            'locationReportCSV',
            'returnsReportCSV',
            'solarInventoryCSV',
            'stockAtDateCSV',
            'truckRunSheetCSV',
            'inventoryReportCSV',
            'orderExportCSV'
        ];
        if(in_array($action, $secure_downloads))
        {
            $this->Security->config("validateCsrfToken", true);
        }
    }

    public function downloadFile()
    {
        $file_name = (isset($this->request->params['args']['file']))? $this->request->params['args']['file']: "";
        $fullPath = APP . "files/" . $file_name;

        //die($fullPath);

        $extension = explode(".", $file_name);
        $ext = end($extension);

        if(!Uploader::isFileExists($fullPath))
        {
            return $this->error(404);
        }

        $this->response->download($fullPath, ["basename" => $file_name, "extension" => $ext]);
    }

    public function solarInventoryCSV()
    {
        $products = $this->item->getItemsForClient($this->client->solar_client_id);
        $cols = array(
            "Name",
            "SKU",
            "Supplier",
            "Owner",
            "On Hand",
            "Allocated",
            "Under QC",
            "Available"
        );
        $rows = array();
        foreach($products as $p)
        {
            $onhand = $this->item->getStockOnHand($p['id']);
            $allocated = $this->item->getAllocatedStock($p['id'], $this->order->fulfilled_id);
            $underqc = $this->item->getStockUnderQC($p['id']);
            $available = $onhand - $allocated - $underqc;
            $owner = ($p['solar_type_id'] > 0)?$this->solarordertype->getSolarOrderType($p['solar_type_id']): "";

            $row = array(
                $p['name'],
                $p['sku'],
                $p['supplier'],
                $owner,
                $onhand,
                $allocated,
                $underqc,
                $available
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "solar_inventory".date("Ymd")]);
    }

    public function solarConsumablesReorderCSV()
    {
        $products = $this->item->getSolarConsumablesReordering();
        $cols = array(
            "Name",
            "SKU",
            "Currently Available",
            "Minimum Reorder Amount"
        );
        $rows = array();
        foreach($products as $p)
        {
            $row = array(
                $p['name'],
                $p['sku'],
                $p['currently_available'],
                $p['minimum_reorder_amount']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "solar_consumables_reorder".date("Ymd")]);
    }

    public function dispatchReportCSV()
    {
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $orders = $this->order->getDispatchedOrdersArray($from, $to, $client_id);
        $cols = array(
            "Date Ordered",
            "Entered By",
            "Date Dispatched",
            "Order Number",
            "Client Order Number",
            "Shipped To",
            'Country',
            'Charge Code',
            'Total Charge',
            'Weight',
            'Shrink Wrap',
            'Bubble Wrap',
            'Pallets',
            "Courier",
            "Consignment ID",
            "Cartons",
            "Comments",
            "Dispatching",
            "Packing",
            "Total Items",
            "Store Order"
        );

        $rows = array();
        $extra_cols = 0;
        foreach($orders as $o)
        {
            $row = array(
                $o['date_ordered'],
                $o['entered_by'],
                $o['date_fulfilled'],
                $o['order_number'],
                $o['client_order_number'],
                str_replace("<br/>", ", ",$o['shipped_to']),
                $o['country'],
                $o['charge_code'],
                $o['charge'],
                $o['weight'],
                $o['shrink_wrap'],
                $o['bubble_wrap'],
                $o['pallets'],
                $o['courier'],
                $o['consignment_id'],
                $o['cartons'],
                $o['comments'],
                strip_tags($o['dispatched_by']),
                strip_tags($o['packed_by']),
                $o['total_items'],
                $o['store_order']
            );
            $extra_cols = max($extra_cols, count($o['csv_items']));
            $i = 1;
            foreach($o['csv_items'] as $array)
            {
                $row[] = $array['name'];
                $row[] = $array['qty'];
                $row[] = $array['pallet'];
                ++$i;
            }
            $rows[] = $row;
        }
        $i = 1;
        while($i <= $extra_cols)
        {
            $cols[] = "Item $i Name";
            $cols[] = "Item $i Qty";
            $cols[] = "Item $i Pallet";
            ++$i;
        }

        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "dispatch_report_".$extra_cols]);
    }

    public function cometCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }

        $cols = array(
            "3PL Order Number",
            "Date",
            "Service Code",
            "DEL/PUP",
            "Customer Name",
            "Customer Address",
            "Customer Address 2",
            "Customer Suburb",
            "Customer Postcode",
            "Customer Phone"
        );

        $rows = array();
        $extra_cols = 0;
        foreach($this->request->data['order_ids'] as $order_id)
        {
            if( $od = $this->order->getOrderDetail($order_id) )
            {
                $name = empty($od['company_name'])? $od['ship_to']: $od['company_name'];
                $phone = preg_replace("/\+61/","0",$od['contact_phone']);
                $phone = preg_replace("/[^\d]/","",$phone);
                $row = array(
                    $od['order_number'],
                    date("d/m/Y"),
                    "HM2",
                    "DEL",
                    $name,
                    $od['address'],
                    $od['address_2'],
                    $od['suburb'],
                    $od['postcode'],
                    $phone
                );
                $items = $this->order->getItemsForOrderNoLocations($order_id);
                $extra_cols = max($extra_cols, count($items));
                $i = 1;
                foreach($items as $item)
                {
                    $row[] = $item['name'];
                    $row[] = $item['sku'];
                    $row[] = $item['qty'];
                    ++$i;
                }
                $rows[] = $row;
            }
        }
        $i = 1;
        while($i <= $extra_cols)
        {
            $cols[] = "Item $i Name";
            $cols[] = "Item $i SKU";
            $cols[] = "Item $i Qty";
            ++$i;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "comet_export".date("Ymd")]);
    }

    public function orderExportCSV()
    {
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        //$client_info = $this->client->getClientInfo($client_id);
        $cols = array(
            "ConID",
            "Receiver Name",
            "Receiver Address1",
            "Receiver Address2",
            "Receiver City",
            "Receiver State",
            "Receiver Postcode",
            "Customer Reference",
            "Special Instruction",
            "Line Reference",
            "Package Description",
            "Item Count",
            "Weight",
            "Length",
            "Width",
            "Height",
            "Qty",
            "Cubic",
            "Receiver Contact Name",
            "Receiver Contact Email",
            "Receiver Contact Mobile",
            "ATL",
            "Dangerous Goods",
            "End of Record"
        );
        $rows = array();
        foreach($this->request->data['order_ids'] as $order_id)
        {
            if( $od = $this->order->getOrderDetail($order_id) )
            {
                $ci = $this->client->getClientInfo($od['client_id']);
                $name = empty($od['company_name'])? $od['ship_to']: $od['company_name'];
                $phone = preg_replace("/\+61/","0",$od['contact_phone']);
                $phone = preg_replace("/[^\d]/","",$phone);

                $items = $this->order->getItemsForOrder($order_id);
                $packages = $this->order->getPackagesForOrder($order_id);
                //$products = $this->getItemsCountForOrder($co['id']);

                $parcels = Packaging::getPackingForOrder($od,$items,$packages);

                foreach($parcels as $i)
                {
                    $weight = ceil($i['pieces'] * $i['weight']);
                    $cubic = round($i['width'] * $i['depth'] * $i['height'] * $i['pieces'] / 1000000, 3);
                    $row = array(
                        "",
                        $name,
                        str_replace(",", " ",$od['address']),
                        str_replace(",", " ",$od['address_2']),
                        $od['suburb'],
                        $od['state'],
                        $od['postcode'],
                        "|".$ci['ref_1']."|".$od['order_number'],
                        "",
                        $od['client_order_id'],
                        "CARTON",
                        $i['pieces'],
                        $weight,
                        ceil($i['width']),
                        ceil($i['depth']),
                        ceil($i['height']),
                        $i['pieces'],
                        $cubic,
                        $od['ship_to'],
                        $od['tracking_email'],
                        $phone,
                        "N",
                        "N",
                        "EOR"
                    );
                    $rows[] = $row;
                }
            }

        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "order_export"], false);
    }

    public function inventoryReportCSV()
    {
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $products = $this->item->getClientInventoryArray( $client_id );
        $cols = array(
            "Name",
            "SKU",
            "Total On Hand",
            "Currently Allocated",
            "Under Quality Control",
            "Total Available"
        );

        $rows = array();
        $extra_cols = 0;
        foreach($products as $p)
        {
            $available = $p['onhand'] - $p['qc_count'] - $p['allocated'];
            $row = array(
                $p['name'],
                $p['sku'],
                $p['onhand'],
                $p['allocated'],
                $p['qc_count'],
                $available
            );
            $extra_cols = max($extra_cols, count($p['locations']));
            $i = 1;
            foreach($p['locations'] as $array)
            {
                $row[] = $array['name'];
                $row[] = $array['onhand'];
                $row[] = $array['allocated'];
                $row[] = $array['qc_count'];
                ++$i;
            }
            $rows[] = $row;
        }
        $i = 1;
        while($i <= $extra_cols)
        {
            $cols[] = "Location $i Name";
            $cols[] = "Location $i On Hand";
            $cols[] = "Location $i Allocated";
            $cols[] = "Location $i Under Quality Control";
            ++$i;
        }

        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "inventory_report"]);
    }

    public function clientDispatchReportCSV()
    {
        $client_id = Session::getUserClientId();
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $hidden = Config::get("HIDE_CHARGE_CLIENTS");
        $orders = $this->order->getDispatchedOrdersArray($from, $to, $client_id);
        $cols = array(
            "Date Ordered",
            "Entered By",
            "Date Dispatched",
            "WMS Order Number",
            "Your Order Number",
            "Shipped To",
            "Items",
            "total Items",
            "Courier",
            "Charge Code",
            "Consignment ID"
        );
        if( !in_array($client_id, $hidden) )
        {
            $cols[] = "Estimated Freight Charge";
        }
        $rows = array();
        foreach($orders as $o)
        {
            $row = array(
                $o['date_ordered'],
                $o['entered_by'],
                $o['date_fulfilled'],
                $o['order_number'],
                $o['client_order_number'],
                str_replace("<br/>", ", ",$o['shipped_to']),
                str_replace("<br/>", "",$o['items']),
                $o['total_items'],
                $o['courier'],
                $o['charge_code'],
                $o['consignment_id']
            );
            if( !in_array($client_id, $hidden) )
            {
                $row[] = $o['charge'];
            }
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "dispatch_report"]);
    }

    public function clientStockMovementCSV()
    {
        $client_id = Session::getUserClientId();
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $exc = array($this->stockmovementlabels->getLabelId('Internal Stock Movement'));
        $movements = $this->itemmovement->getItemMovementsArray($client_id, $from, $to, $exc);
        $cols = array(
            "Date",
            "Order Number/Reference",
            "Your Order Number",
            "Deliver To",
            "SKU",
            "Product",
            "Number In",
            "Number Out",
            "Reason"
        );
        $rows = array();
        foreach($movements as $m)
        {
            $row = array(
                $m['date'],
                $m['order_number'],
                $m['client_order_id'],
                $m['address'],
                $m['sku'],
                $m['name'],
                $m['qty_in'],
                $m['qty_out'],
                $m['reason']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "product_movement_report"]);
    }

    public function stockMovementCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $movements = $this->itemmovement->getItemMovementsArray($client_id, $from, $to);
        $cols = array(
            "Date",
            "SKU",
            "Product",
            "Number In",
            "Number Out",
            "Reason",
            "Reference/Order Number",
            "Location",
            "Entered By"
        );
        $rows = array();
        foreach($movements as $m)
        {
            $row = array(
                $m['date'],
                $m['sku'],
                $m['name'],
                $m['qty_in'],
                $m['qty_out'],
                $m['reason'],
                $m['order_number'],
                $m['location'],
                $m['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "stock_movement_report"]);
    }

    public function clientStockSummaryCSV()
    {
        $client_id = Session::getUserClientId();
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $exc = array($this->stockmovementlabels->getLabelId('Internal Stock Movement'));
        $movements = $this->itemmovement->getItemMovementsSummaryArray($client_id, $from, $to, $exc);
        $cols = array(
            "Name",
            "SKU",
            "Total In",
            "Total Out",
            "Currently On Hand"
        );
        $rows = array();
        foreach($movements as $m)
        {
            $row = array(
                $m['name'],
                $m['sku'],
                $m['total_in'],
                $m['total_out'],
                $m['on_hand']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "product_movement_summary"]);
    }

    public function returnsReportCSV()
    {
        $client_id = Session::getUserClientId();
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $returns = $this->orderreturn->getReturnedOrdersArray($from, $to, $client_id);
        $cols = array(
            "Date Returned",
            "Item",
            "WMS Order Number",
            "Your Order Number",
            "Reason",
            "Entered By"
        );
        $rows = array();
        foreach($returns as $o)
        {
            $row = array(
                $o['return_date'],
                $o['item_name'],
                $o['order_number'],
                $o['client_order_number'],
                $o['reason'],
                $o['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "returns_report"]);
    }

    public function goodsInReportCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $goodsin = $this->inwardsgoods->getInwardsGoodsArray($from, $to);
        $cols = array(
            "Date",
            "Client",
            "Pallets",
            "Cartons",
            "Entered By"
        );
        $rows = array();
        foreach($goodsin as $gi)
        {
            $row = array(
                $gi['date'],
                $gi['client_name'],
                $gi['pallets'],
                $gi['cartons'],
                $gi['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "goodsin_report"]);
    }

    public function goodsinSummaryCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $summary = $this->inwardsgoods->getSummaryArray($from, $to);
        $cols = array(
            "Client",
            "Pallets",
            "Cartons"
        );
        $rows = array();
        foreach($summary as $s)
        {
            $row = array(
                $s['client_name'],
                $s['pallets'],
                $s['cartons']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "goodsin_summary"]);
    }

    public function goodsoutSummaryCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $summary = $this->outwardsgoods->getSummaryArray($from, $to);
        $cols = array(
            "Client",
            "Pallets",
            "Cartons",
            "Satchels"
        );
        $rows = array();
        foreach($summary as $s)
        {
            $row = array(
                $s['client_name'],
                $s['pallets'],
                $s['cartons'],
                $s['satchels']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "goodsout_summary"]);
    }

    public function unloadedContainersCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $unloaded_containers = $this->unloadedcontainer->getUnloadedContainersArray($from, $to);
        $cols = array(
            "Date",
            "Client",
            "Container Size",
            "Load Type",
            "Item Count",
            "Repalletising",
            "Old pallet Disposal",
            "Entered By"
        );
        $rows = array();
        foreach($unloaded_containers as $uc)
        {
            $row = array(
                $uc['date'],
                $uc['client_name'],
                $uc['container_size'],
                $uc['load_type'],
                $uc['item_count'],
                $uc['repalletising'],
                $uc['disposal'],
                $uc['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "unloaded_containers"]);
    }

    public function truckRunSheetCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $runs = $this->truckusage->getRunSheetArray($from, $to);
        $cols = array(
            "Date",
            "Client",
            "Order Number",
            "Suburb",
            "Charge",
            "Entered By"
        );
        $rows = array();
        foreach($runs as $r)
        {
            $row = array(
                $r['date'],
                $r['client_name'],
                $r['order_number'],
                $r['suburb'],
                $r['charge'],
                $r['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "truck_runsheet"]);
    }

    public function goodsOutReportCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $goodsout = $this->outwardsgoods->getOutwardsGoodsArray($from, $to);
        $cols = array(
            "Date",
            "Client",
            "Pallets",
            "Cartons",
            "Satchels",
            "Entered By"
        );
        $rows = array();
        foreach($goodsout as $gi)
        {
            $row = array(
                $gi['date'],
                $gi['client_name'],
                $gi['pallets'],
                $gi['cartons'],
                $gi['satchels'],
                $gi['entered_by']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "goodsout_report"]);
    }

    public function stockAtDateCSV()
    {
        $client_id = Session::getUserClientId();
        //echo "<pre>",print_r($this->request),"</pre>"; die();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $stock = $this->itemmovement->getStockAtDateArray($client_id, $date);
        $cols = array(
            "Name",
            "SKU",
            "Stock On Hand At ".date("d/m/Y", $date)
        );
        $rows = array();
        foreach($stock as $i)
        {
            $row = array(
                $i['name'],
                $i['sku'],
                $i['on_hand']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "stock_at_date"]);
    }

    public function locationreportCSV()
    {
        $locations = $this->location->getLocationUsage();
        $cols = array(
            "Location",
            "Oversize",
            "Client",
            "Item",
            "SKU",
            "Count"
        );
        $rows = array();
        foreach($locations as $l)
        {
            $os = ($l['oversize'] > 0)? "Yes":"No";
            $row = array(
                $l['location'],
                $os,
                $l['client_name'],
                $l['name'],
                $l['sku'],
                $l['qty']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "location_report"]);
    }

    public function emptyBaysCSV()
    {
        $locations = $this->location->getEmptyLocations();
        $cols = array(
            "Location"
        );
        $rows = array();
        foreach($locations as $l)
        {
            $row = array(
                $l['location']
            );
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "empty_bays"]);
    }

    public function clientBayUsageCSV()
    {
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
            }
        }
        $bays = $this->clientsbays->getBayUsage($from, $to);
        $cols = array(
            'Client'
        );
        foreach($bays['fridays'] as $f)
        {
            $cols[] = $f['string'];
            $cols[] = "";
            $cols[] = "";
        }
        $rows = array();
        $c = 0;
        $row = array();
        foreach($bays['fridays'] as $f)
        {
            if($c == 0)
                $row[] = "";
            $row[] = "Standard Bays";
            $row[] =  "Oversize Bays";
            $row[] =  "Pickfaces";
            ++$c;
        }
        $rows[] = $row;
        foreach($bays['data'] as $client_name => $carray)
        {
            $row = array(
                $client_name
            );
            foreach($bays['fridays'] as $f)
            {
                $usage = (isset($carray[$f['string']]['standard']))? round($carray[$f['string']]['standard']) : 0;
                $row[] = $usage;
                $usage = (isset($carray[$f['string']]['oversize']))? round($carray[$f['string']]['oversize']) : 0;
                $row[] = $usage;
                $usage = (isset($carray[$f['string']]['pickfaces']))? round($carray[$f['string']]['pickfaces']) : 0;
                $row[] = $usage;
            }
            $rows[] = $row;
        }
        $expire=time()+60;
        setcookie("fileDownload", "true", $expire, "/");
        $this->response->csv(["cols" => $cols, "rows" => $rows], ["filename" => "client_bay_usage"]);
    }

    public function printLocationBarcodes()
    {
        $pdf = new Mympdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 12,
            'margin_bottom' => 12
        ]);
        $locations  = $this->location->getAllLocations();
        $html = $this->view->render(Config::get('VIEWS_PATH') . 'pdf/locations.php', [
            'locations'    =>  $locations
        ]);
        $stylesheet = file_get_contents(STYLES."barcodes.css");
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output();
    }

    public function usefulBarcodes()
    {
        $pdf = new Mympdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 5,
            'margin_bottom' => 5
        ]);
        $items  = $this->item->getItemsForClient(51);
        //echo "<pre>",print_r($items),"</pre>"; die();
        $html = $this->view->render(Config::get('VIEWS_PATH') . 'pdf/usefulBarcodes.php', [
            'items'    =>  $items
        ]);
        $stylesheet = file_get_contents(STYLES."barcodes.css");
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output();
    }

    public function isAuthorized(){

        $action = $this->request->param('action');
        $role = (Session::isAdminUser())? 'admin' : Session::getUserRole();
        $resource = "downloads";

        $role = Session::getUserRole();
        if( isset($role) && ($role === "admin"  || $role === "super admin") )
        {
            return true;
        }

        //warehouse users
        Permission::allow('warehouse', $resource, array(

        ));

        //solar admin users
        Permission::allow('solar admin', $resource, array(
            'solarInventoryCSV',
            'solarConsumablesReorderCSV'
        ));

        //client users
        Permission::allow('client', $resource, array(
            "clientDispatchReportCSV",
            "returnsReportCSV",
            "clientStockMovementCSV",
            "clientStockSummaryCSV",
            "stockAtDateCSV",
            "downloadFile"
        ));

        //remove an allocation
        Permission::deny('admin', $resource, array(
            "printLocationBarcodes"
        ));

        return Permission::check($role, $resource, $action);
        return false;

    }
}
