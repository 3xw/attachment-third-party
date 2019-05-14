<?php
namespace Attachment\ThirdParty\Mover;

use Cake\Core\InstanceConfigTrait;
use Attachment\Model\Entity\Attachment;

class BaseMover
{
  use InstanceConfigTrait;

  protected $_defaultConfig = [];

  public function __construct($config = [])
  {
    $this->setConfig($config);
  }

  public function move(Attachment $attachment, $progressCb = null, $successCb = null, $errorCb = null)
  {

  }
}
