<?php
namespace Attachment\ThirdParty\Mover;

use Attachment\Model\Entity\Attachment;
use Attachment\ThirdParty\Http\Google\Client;
use Google_Service_YouTube;

// composer require google/apiclient:^2.0

class YoutubeMover extends BaseMover
{
  protected $_defaultConfig = [
    'client' => [

      // https://developers.google.com/console
      'client_id' => '',
      'client_secret' => '',
      'redirect_uri' => null,
      'state' => null,

      // Simple API access key, also from the API console. Ensure you get
      // a Server key, and not a Browser key.
      'developer_key' => '',
    ]
  ];

  protected $_client;
  protected $_service;

  public function __construct($config = [])
  {
    parent::__construct($config);

    if (php_sapi_name() != 'cli') throw new \Exception('This application must be run on the command line.');

    $this->_client = new Client($this->getConfig('client'));
    $this->_client->setScopes('https://www.googleapis.com/auth/youtube');
    $this->_service = new Google_Service_YouTube($this->_client);

    $this->_client->prepareScopes();
    debug($this->_client->getAccessToken());
  }

  public function move(Attachment $attachment, $progressCb = null, $successCb = null, $errorCb = null)
  {
    //debug($attachment);
  }
}
