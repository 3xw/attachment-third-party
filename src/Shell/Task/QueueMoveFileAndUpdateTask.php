<?php
namespace Attachment\ThirdParty\Shell\Task;

class QueueMoveFileAndUpdateTask extends QueueMoveFileTask
{

  public function add()
  {
    $this->err('!!! Queue Move File And Update cannot be added via Console !!!');
    $this->hr();
  }

  public function run(array $data, $id)
  {
    $this->success = parent::run($data, $id);

    // TODO: update and save $this->attachment

    //return result
    return $this->success;
  }
}
