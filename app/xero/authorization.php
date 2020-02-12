<?php
/**
 * The authorization class.
 * Keeps track of tokens and authorization for access to the xero API
 *
 * @author     Mark Solly <mark@baledout.com.au>
 */
use Calcinai\OAuth2\Client\Provider\Xero;

class Authorization{

    public $provider;
    public $token_details;
    public $xero_app;
    public $org;

    private $table = "xero_authorization";


    /* Class constructor */
    public function __construct(){
        $db = Database::openConnection();
        $this->provider = new Xero([
            'clientId'      => Config::get('XEROCLIENTID'),
            'clientSecret'  => Config::get('XEROCLIENTSECRET'),
            'redirectUri'   => Config::get('XEROREDIRECTURL'),
        ]);
        $this->token_details = $db->queryByID($this->table, 1);
        if($this->tokenExpired())
        {
            //Gotta get a new one
            $newAccessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $this->token_details['refresh_token']
            ]);
            // Save my new token, expiration and refresh token
            $db->updateDatabaseFields($this->table, array(
                'token' => $newAccessToken->getToken(),
                'refresh_token' => $newAccessToken->getRefreshToken(),
                'expires'       => $newAccessToken->getExpires()
            ), 1);
            $this->token_details = $db->queryByID($this->table, 1);
        }

        $this->xero_app = new \XeroPHP\Application(
            $this->token_details['token'],
            $this->token_details['tenant_id']
        );
        $this->org = $this->xero_app->load(\XeroPHP\Models\Accounting\Organisation::class)->execute();
    }

    private function tokenExpired()
    {
        return(time() < $this->token_details['expires']);
    }
}