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
    "BALEDOUT_ADDRESS" => array(
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
    ),
    "ADMIN_PAGES"   =>  array(
        'orders' => array(
            'add-order' =>  true,
            'order-update'			    => false,
			'process-backorders'	    =>	false,
			'order-summaries'		    =>	true,
            'edit-address'              =>  false,
            'order-edit'                =>  false,
            'edit-customer'             =>  false,
            'order-search'              =>  true,
            'order-search-results'      =>  false,
            'order-details'             =>  false,
            'order-dispatching'         =>  true,
            'view-orders'               =>  true,
            'address-update'            =>  false,
            'items-update'              =>  false,
            'order-importing'           =>  true,
        ),
		'clients'	=> array(
			'view-clients'	=> true,
			'add-client'	=> true,
			'edit-client'	=> false,
		),
		'reports'			=> array(

		),
        'staff'             => array(
        ),
        'data-entry'    =>  array(
        ),
		'site-settings'		=> array(
		),
        'financials'    =>  array(
        ),
        'admin-only'    => array(
            'super_admin_only'  => true,
            'eparcel-shipment-deleter'  => true,
            'dispatched-orders-updater' => false,
            'client-bay-fixer'  => true
        ),
    )
    /**
    * Order status
    *
    */

);
