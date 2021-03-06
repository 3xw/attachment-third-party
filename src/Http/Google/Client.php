<?php
namespace Attachment\ThirdParty\Http\Google;

use Google_Client;
use Cake\Core\InstanceConfigTrait;

class Client extends Google_Client
{
  use InstanceConfigTrait;

  protected $_defaultConfig = [];

  // for cli usage ( create and download a OAuth Client type Other )
  const CREDENTIALS_PATH = CONFIG.'google'.DS.'credentials.json';
  const TOKEN_PATH = CONFIG.'google'.DS.'token.json';

  public function __construct($config = [])
  {
    $this->setConfig($config);
    parent::__construct($this->getConfig());
  }

  public function enableCliOAuth($scopes, $credentials = self::CREDENTIALS_PATH, $token = self::TOKEN_PATH)
  {
    if (php_sapi_name() != 'cli') throw new \Exception('This application must be run on the command line.');

    $this->setScopes($scopes);
    $this->setAuthConfig($credentials);
    $this->setAccessType('offline');
    $this->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = $token;
    if (file_exists($tokenPath))
    {
      $accessToken = json_decode(file_get_contents($tokenPath), true);
      $this->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($this->isAccessTokenExpired())
    {
      // Refresh the token if possible, else fetch a new one.
      if ($this->getRefreshToken()) $this->fetchAccessTokenWithRefreshToken($this->getRefreshToken());
      else
      {
        // Request authorization from the user.
        $authUrl = $this->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $this->fetchAccessTokenWithAuthCode($authCode);
        $this->setAccessToken($accessToken);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) throw new Exception(join(', ', $accessToken));
      }
      // Save the token to a file.
      if (!file_exists(dirname($tokenPath))) mkdir(dirname($tokenPath), 0700, true);
      file_put_contents($tokenPath, json_encode($this->getAccessToken()));
    }

    return $this;
  }
}
