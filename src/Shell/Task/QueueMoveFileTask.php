<?php
namespace Attachment\ThirdParty\Shell\Task;

use Queue\Shell\Task\QueueTask;
use Attachment\Fly\Profile;

class QueueMoveFileTask extends QueueTask
{
  public function add()
 {
  $this->err('!!! Queue Move File cannot be added via Console !!!');
  $this->hr();
 }

  public function run(array $data, $id)
  {
    $attachment = $data['attachment'];
    $handlerClass = $data['handler'];
    $config = $data['config'];

    $handler = new $handlerClass($attachment, $config);
  }
}
