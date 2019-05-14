<?php
namespace Attachment\ThirdParty\Http\Google;

use Google_Client;
use Cake\Core\InstanceConfigTrait;

class Client extends Google_Client
{
  use InstanceConfigTrait;

  protected $_defaultConfig = [

    // https://developers.google.com/console
    'client_id' => '',
    'client_secret' => '',
    'redirect_uri' => null,
    'state' => null,

    // Simple API access key, also from the API console. Ensure you get
    // a Server key, and not a Browser key.
    'developer_key' => ''
  ];

  public function __construct($config = [])
  {
    $this->setConfig($config);
    parent::__construct($this->getConfig());
  }
}
