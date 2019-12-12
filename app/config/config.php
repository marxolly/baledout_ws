<?php

 /**
  * This file contains configuration for the application.
  * It will be used by app/core/Config.php
  *
  * @author     Mark Solly <mark@baledout.com.au>
  */

return array(
    /**
     * Configuration for: Paths
     * Paths from App directory
     */
    "VIEWS_PATH"            => APP . "views/",
    "ERRORS_PATH"           => APP . "views/errors/",
    "LOGIN_PATH"            => APP . "views/login/",
    "ADMIN_VIEWS_PATH"      => APP . "views/admin/",
    "EMAIL_TEMPLATES_PATH"  => APP . "email_templates/",
    "EMAIL_ATTACHMENTS_PATH"  => APP . "email_attachments/",

    /**
     * Configuration for: Cookies
     *
     * COOKIE_RUNTIME: How long should a cookie be valid by seconds.
     *      - 1209600 means 2 weeks
     *      - 604800 means 1 week
     * COOKIE_DOMAIN: The domain where the cookie is valid for.
     *      COOKIE_DOMAIN mightn't work with "localhost", ".localhost", "127.0.0.1", or ".127.0.0.1". If so, leave it as empty string, false or null.
     *      @see http://stackoverflow.com/questions/1134290/cookies-on-localhost-with-explicit-domain
     *      @see http://php.net/manual/en/function.setcookie.php#73107
     *
     * COOKIE_PATH: The path where the cookie is valid for. If set to '/', the cookie will be available within the entire COOKIE_DOMAIN.
     * COOKIE_SECURE: If the cookie will be transferred through secured connection(SSL). It's highly recommended to set it to true if you have secured connection
     * COOKIE_HTTP: If set to true, Cookies that can't be accessed by JS - Highly recommended!
     * COOKIE_SECRET_KEY: A random value to make the cookie more secure.
     *
     */
    "COOKIE_EXPIRY"         => 1209600,
    "SESSION_COOKIE_EXPIRY" => 604800,
    "COOKIE_DOMAIN"         => '',
    "COOKIE_PATH"           => '/',
    "COOKIE_SECURE"         => true,
    "COOKIE_HTTP"           => true,
    "COOKIE_SECRET_KEY"     => "fg&70-GF^!a{f64r5@g38l]#kQ4B+21%",

    /**
     * Configuration for: Encryption Keys
     *
     */
    "ENCRYPTION_KEY"    => "3¥‹a0ef@!$251Êìcef08%&",
    "HMAC_SALT"         => "a8C7n7^Ed0%8Qfd9K4m6d$86Dab",
    "HASH_KEY"          => "z5F9Mp7Lf2cH",

    /**
     * Configuration for Email
     *
     */
    "EMAIL_FROM"        => "no-reply@baledout.com.au",
    "EMAIL_FROM_NAME"   => "Baledout Pty Ltd",
    "EMAIL_REPLY_TO"    => "no-reply@baledout.com.au",

    "EMAIL_PASSWORD_RESET_URL" => PUBLIC_ROOT . "login/resetPassword",

    /**
     * Configuration for: Hashing strength
     *
     * It defines the strength of the password hashing/salting. "10" is the default value by PHP.
     * @see http://php.net/manual/en/function.password-hash.php
     *
     */
    "HASH_COST_FACTOR" => "10",

    /**
     * Configuration for: Pagination
     *
     */
    "PAGINATION_DEFAULT_LIMIT" => 10,

    /*************************************************************************
    * XERO API constants
    **************************************************************************/
    'XEROCONSUMERKEY'     => 'OUWBI1XCHJU9RHOJXTIBYFHNGHMV1R',
    'XEROCONSUMERSECRET'  => 'NBFMLJLWPHWIRAUJL0CDCYL5KA2VE4',
    /*************************************************************************
    * Baledout Address
    **************************************************************************/
    "THREEPL_ADDRESS" => array(
      	'address'	=>	'',
		'address_2'	=>	'',
		'suburb'	=>	'',
		'city'		=>	'',
		'state'		=>	'SA',
		'country'	=>	'AU',
		'postcode'	=>	''
	),
    /*************************************************************************
    * Pages and icons
    **************************************************************************/
    "MENU_ICONS"    =>  array(
        'orders'            =>  'fas fa-truck',
        'ordering'          =>  'fas fa-cash-register',
		'clients'	        =>	'fas fa-user-tie',
		'products'	        =>	'fas fa-dolly',
		'inventory'	        =>	'fas fa-tasks',
		'reports'	        =>	'far fa-chart-bar',
		'site-settings'	    =>	'fas fa-cog',
		'staff'		        =>	'fas fa-users',
        'stock-movement'    =>  'fas fa-dolly',
        'data-entry'        =>  'fas fa-indent',
        'sales-reps'		=>	'fas fa-users',
        'solar-teams'		=>	'fas fa-users',
        'stores'            =>  'fas fa-store-alt',
        'downloads'         =>  'fas fa-download',
        'financials'        =>  'fas fa-file-invoice-dollar',
        'admin-only'        =>  'fas fa-lock',
        'scheduling'        =>  'far fa-calendar-alt',
        'solar-jobs'        =>  'fas fa-tools'
    ),
    "SOLAR_ADMIN_PAGES" => array(
        'solar-jobs'  => array(
            'add-solar-install'             => true,
            'update-solar-job'          => false,
            'add-service-job'           => true,
            'view-installs'                 => true,
            'job-search'                => true,
            'edit-servicejob'           => false,
            'update-service-details'    => false,
            'view-service-jobs'         => true,
            'update-install-details'    => false,
            'install-items-update'      => false
        ),
        'products'	=> array(
            'view-products'			=> true,
            'add-product'			=> true,
            'edit-product'			=> false,
        ),
        'inventory' => array(
            'view-inventory'    => true,
            'view-solar-inventory'  => false
        ),
        'solar-teams'   => array(
            'add-team'  => true,
            'edit-team' => false,
            'view-teams'    => true
        ),
        'scheduling'    => array(
            'view-schedule' => true
        ),
        'reports'   => array(
            'jobs-report'       => true,
            'solar-returns-report'    => true,
            'solar-consumables-reorder' => true
        ),
    ),
    "SOLAR_PAGES"   => array(
        'solar-jobs'  => array(
            'view-installs'                 => true,
            'view-service-jobs'         => true
        ),
        'ordering'  => array(
            'order-consumables' => true
        ),
        'scheduling'    => array(
            'view-schedule' => true
        ),
    ),
    "ADMIN_PAGES"   =>  array(
        'orders' => array(
            'add-order' =>  true,
            'order-update'			    => false,
			'process-backorders'	    =>	false,
			'order-summaries'		    =>	true,
            //'add-b2B-order'             =>  true,
            'edit-address'              =>  false,
            'order-edit'                =>  false,
            'edit-customer'             =>  false,
            'order-search'              =>  true,
            'order-search-results'      =>  false,
            'order-picking'             =>  true,
            'order-details'             =>  false,
            'order-packing'             =>  true,
            'order-dispatching'         =>  true,
            'truck-usage'               =>  true,
            'view-orders'               =>  true,
            'address-update'            =>  false,
            'items-update'              =>  false,
            'order-importing'           =>  true,
            'view-storeorders'          => true,
            'view-pickups'              => false,
            'record-pickup'   => true,
            'order-csv-upload'  => true,
            'manage-swatches'   => true,
            'add-serials'   => true
        ),
        'solar-jobs'  => array(
            'add-solar-install'             => true,
            'add-solar-install-new'             => false,
            'update-solar-job'          => false,
            'add-service-job'           => true,
            'view-installs'                 => true,
            'add-origin-job'            => false,
            'add-tlj-job'               => false,
            'add-solargain-job'               => false,
            'job-search'                => true,
            'edit-servicejob'           => false,
            'update-service-details'    => false,
            'view-service-jobs'         => true,
            'update-install-details'    => false,
            'install-items-update'      => false
        ),
		'clients'	=> array(
			'view-clients'	=> true,
			'add-client'	=> true,
			'edit-client'	=> false,
		),
		'products'	=> array(
			'view-products'			=> true,
			'add-product'			=> true,
			'edit-product'			=> false,
			//'bulk-product-upload'	=>	true,
            'pack-items-edit'       =>  true,
            'collections-edit'      =>  true,
            'product-search'        =>  true,
		),
        'inventory'   =>  array(
            'view-inventory'		=>	true,
            'pack-items-manage'     =>  true,
            //'product-to-location'   =>  true,
            'scan-to-inventory'     =>  true,
            //'client-locations'      =>  true,
            'product-movement'      =>  false,
            //'location-scanner'      =>  true,
            //'returns-input'         =>  true,
            'goods-out'             =>  true,
            'goods-in'               =>  true,
            'add-subtract-stock'    =>  false,
            'quality-control'       =>  false,
            //'replenish-pickface'    => true,
            'transfer-location' => true,
            'solar-returns' => true,
            'move-bulk-items'   => true
        ),
		'reports'			=> array(
          	//'product-movement-summary'	=>	true,
            //'product-by-location'   =>  true,
			'stock-movement-report'	=>	true,
			//'user-activity-report'		=>	true,
			//'client-activity-report'	=>	true,
			'dispatch-report'			=>	true,
			//'backorder-report'		=>	true,
			'inventory-report'			=>	true,
            'location-report'           =>  true,
			//'audit-log'				=>	true,
            'client-space-usage-report'   =>  true,
            //'hunters-check'             =>  true,
            //'client-daily-reports'      =>  true,
            'goods-out-report'          =>  true,
            'goods-out-summary'         =>  true,
            'goods-in-report'          =>  true,
            'goods-in-summary'         =>  true,
            //'staff-time-report'        =>  true,
            //'staff-time-sheets'         =>  true,
            //'eparcel-check'             =>  true,
            'stock-at-date'             =>  true,
            //'returns-report'             =>  true,
            //'empty-bay-report'           =>  true,
            //'pick-error-report'         =>  true,
            'unloaded-containers-report'       =>  true,
            'truck-run-sheet'       =>  true,
            '3pl-dispatch-report'   =>  false,
            '3pl-stock-movement-report' =>  false,
            'empty-bay-report'      => true,
            //'client-daily-reports'  => true,
            'pickups-report'    => true,
            'solar-returns-report'    => true,
            'solar-consumables-reorder' => true,
            'swatches-report'       => true,
            'order-serial-numbers-report'   => true,
            '3pl-order-serials-report'  => false
		),/*
        'sales-reps'        =>  array(
            'view-reps'                 =>  true,
            'add-sales-rep'             =>  true,
            'edit-sales-rep'            =>  false,
            'ship-to-reps'              =>  true
        ),*/
        'solar-teams'   =>array(
            'add-team'  => true,
            'edit-team' => false,
            'view-teams'    => true
        ),/*
        'stores'        =>  array(
            'view-stores'           =>  true,
            'add-store'             =>  true,
            'edit-store'            =>  false
        ),*/
        /*
        'staff'             => array(
            'time-sheets'   =>  true,
            'client-time-usage'  =>  true,
            'close-staff-task'  =>  false
        ),
        */
        'data-entry'    =>  array(
            'container-unloading'      => true,
            'error-data'                =>  true,
            'incoming-shipments'        =>  true
        ),
		'site-settings'		=> array(
			'order-status'				=> 	true,
			'stock-movement-reasons'	=> 	true,
            'locations'                 =>  true,
            'staff'                     =>  true,
            'manage-users'	            =>	true,
            'packing-types'             =>  true,
            //'store-chains'              =>  true,
            'user-roles'                =>  true,
            //'pickfaces'                 => true,
            'couriers'                  => true,
            'solar-order-types'   => true,
            'edit-user-profile'     => false
		),
        'financials'    =>  array(
            //'hunters-check' => true,
            'directfreight-check'   => true
        ),
        'downloads' => array(
            'super_admin_only'  => true,
            'print-location-barcodes'   => true,
            'useful-barcodes'   => true
        ),
        'admin-only'    => array(
            'super_admin_only'  => true,
            'eparcel-shipment-deleter'  => true,
            'dispatched-orders-updater' => false,
            'client-bay-fixer'  => true
        ),
    ),
    'WAREHOUSE_PAGES' => array(
        'orders'      => array(
            'order-picking'     =>  true,
            'order-packing'     =>  true,
            'order-dispatching'         =>  true,
            'view-orders'               =>  true,
            'order-search'              =>  true,
            'order-search-results'      =>  false,
        ),
        'products'    =>  array(
            'view-products'			=> true,
            'add-product'           =>  true,
            'edit-product'			=> false,
             'product-search'        =>  true,

        ),
        'inventory'     =>  array(
            'view-inventory'		=>	true,
            'product-to-location'   =>  true,
            'scan-to-inventory'     =>  true,
            //'client-locations'      =>  true,
            'product-movement'      =>  false,
            'goods-out'             =>  true,
            'goods-in'               =>  true,
            'add-subtract-stock'    =>  false,
            'quality-control'       =>  false,
            'pack-items-manage'     =>  true,
            //'replenish-pickface'    => true
        ),
        'staff'   =>  array(
            'time-sheets'   =>  true,
            'client-time-usage'  =>  true
        )
    ),
    'CLIENT_PAGES' => array(
        'orders'			=>	array(
			'client-orders'		=>	true,
			'order-detail'	    =>	false,
			'order-tracking'	=>	false,
            'add-order'         =>  true,
            'bulk-upload-orders'     =>  true,
            //'book-pickup'       => true,
            //'add-origin-order'  => true
		),
		'inventory'			=>	array(
			'client-inventory'	=>	true,
            'expected-shipments'    =>  true,
            'register-new-stock'    => true
		),
        'reports'           =>  array(
            'dispatch-report'   =>  true,
            'stock-at-date'             =>  true ,
            'returns-report'             =>  true,
            'stock-movement-report'     =>  true,
            'stock-movement-summary'    =>  true,
            'client-dispatch-report'    =>  false,
            'client-stock-movement-report'  =>  false
        )
    ),
    /**
    * Order status
    *
    */

);
