<?php
namespace Attachment\ThirdParty\Mover;

use Attachment\Model\Entity\Attachment;
use Attachment\ThirdParty\Http\Google\Client;
use Google_Service_YouTube;

// composer require google/apiclient:^2.0
// + create and download a OAuth Client type Other

class YoutubeMover extends BaseMover
{
  protected $_defaultConfig = [
    'client' => [
      'application_name' => 'Attachment\ThirdParty\Mover\YoutubeMover',
    ],
    'credentials' => CONFIG.'google'.DS.'credentials.json',
    'token' => CONFIG.'google'.DS.'token.json'
  ];

  protected $_client;
  protected $_service;

  public function __construct($config = [])
  {
    parent::__construct($config);
    $this->_client = (new Client($this->getConfig('client')))
    ->enableForCliWithOAuthClient(
      $this->getConfig('credentials'),
      $this->getConfig('token'),
      [
        Google_Service_YouTube::YOUTUBE,
        Google_Service_YouTube::YOUTUBE_FORCE_SSL,
        Google_Service_YouTube::YOUTUBE_READONLY,
        Google_Service_YouTube::YOUTUBE_UPLOAD
      ]
    );
  }

  public function move(Attachment $attachment, $progressCb = null, $successCb = null, $errorCb = null)
  {
    //debug($attachment);
  }
}
