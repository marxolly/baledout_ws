<?php
use Calcinai\OAuth2\Client\Provider\Xero;

include __DIR__ . '/../vendor/autoload.php';

session_start();

$provider = new Xero([
    'clientId' => '62609541B59A46CAB4AC6F374ED3B0F3',
    'clientSecret' => '4-jtbm47vaG4BmDt5oTfg4V-_z2XLN0N_gLOVldpuDQInoBR',
    'redirectUri' => 'https://portal.baledout.com.au/calcinai_demo.php',
]);



if (!isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $options = [
            'scope' => ['openid email profile offline_access accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
    ];
    $authUrl = $provider->getAuthorizationUrl($options);



    $_SESSION['oauth2state'] = $provider->getState();
   // echo $authUrl,"<pre>",print_r($_SESSION),"</pre>"; die();
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);


    //If you added the openid/profile scopes you can access the authorizing user's identity.
    $identity = $provider->getResourceOwner($token);
    echo "IDENTITY<pre>",print_r($identity),"</pre>";

    //Get the tenants that this user is authorized to access
    $tenants = $provider->getTenants($token);
    echo "TENANTS<pre>",print_r($tenants),"</pre>";

    echo "<p>TOKEN: ".$token->getToken()."</p>";
    echo "<p>REFRESH TOKEN: ".$token->getRefreshToken()."</p>";
    echo "<p>ID TOKEN: ".$token->getValues()["id_token"] ."</p>";
    echo "<p>TENANT ID: ".$tenants[0]->tenantId() ."</p>";
}

//The above is only required for the authorization process
//At this  point, you can be using your stored token and desired tenant-id to
//construct the XeroPHP application and make authenticated requests

//For the purposes of the demo, both of these parameters are used from above, but they should be stored.
$application = new \XeroPHP\Application(
    $token->getToken(),
    $tenants[0]->tenantId
);

$org = $application->load(\XeroPHP\Models\Accounting\Organisation::class)->execute();
echo "ORG<pre>",print_r($org),"</pre>";


//$provider->disconnect($token, $tenants[0]->id);


?>