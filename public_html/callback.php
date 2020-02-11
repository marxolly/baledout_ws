<?php
ini_set('display_errors', 'On');
    require __DIR__ . '/../vendor/autoload.php';
    require_once('storage.php');

    // Storage Classe uses sessions for storing token > extend to your DB of choice
    $storage = new StorageClass();

    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => '62609541B59A46CAB4AC6F374ED3B0F3',
        'clientSecret'            => '4-jtbm47vaG4BmDt5oTfg4V-_z2XLN0N_gLOVldpuDQInoBR',
        'redirectUri'             => 'https://portal.baledout.com.au/callback.php',
        'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
        'urlAccessToken'          => 'https://identity.xero.com/connect/token',
        'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
    ]);

    // If we don't have an authorization code then get one
    if (!isset($_GET['code'])) {
        echo "Something went wrong, no authorization code found";
        exit("Something went wrong, no authorization code found");

    // Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        echo "Invalid State";
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    } else {

        try {
            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$accessToken->getToken() );

            $config->setHost("https://api.xero.com");
            $identityInstance = new XeroAPI\XeroPHP\Api\IdentityApi(
                new GuzzleHttp\Client(),
                $config
            );

            $result = $identityInstance->getConnections();

            // Save my tokens, expiration tenant_id
            $storage->setToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $result[0]->getTenantId(),
                    $accessToken->getRefreshToken(),
                    $accessToken->getValues()["id_token"]
            );

            header('Location: ' . './authorizedResource.php');
            exit();

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            echo "Callback failed";
            echo "<pre>",print_r($e),"</pre>";
            exit();
        }
    }


?>