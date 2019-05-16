<?php
namespace Attachment\ThirdParty\Shell\Task;

use Queue\Shell\Task\QueueTask;

class QueueMoveFileTask extends QueueTask
{
  public $neededData = ['attachment','mover'];

  public $progress;

  public $success = false;

  public $attachment;

  public $Attachments;

  public function initialize()
  {
    parent::initialize();
    $this->Attachments = $this->loadModel('Attachment.Attachments');
  }

  public function add()
  {
    $this->err('!!! Queue Move File cannot be added via Console !!!');
    $this->hr();
  }

  public function run(array $data, $id)
  {
    // controll
    foreach($this->neededData as $needed) if(empty($data[$needed])) throw new \Exception("QueueMoveFileTask: Missing task data: ".$needed);

    // setup
    $this->attachment = $data['attachment'];
    $moverClass = $data['mover'];
    $config = empty($data['config'])? []: $data['config'];

    // init
    $mover = new $moverClass($config);

    // progress
    $this->out('processing : '.$this->attachment->name);
    $this->progress = $this->helper('Progress');
    $this->progress->init(['total' => $this->attachment->size,'width' => 0]);
    $this->progress->draw();

    // upload
    $mover->move($this->attachment, [$this, 'progressHandler'], [$this, 'successHandler'], [$this,'errorHandler']);

    //return result
    return $this->success;
  }

  public function progressHandler($attachment, $size, $uploadedBytes, $bytes)
  {
    $this->progress->increment($bytes);
    $this->progress->draw();
  }

  public function successHandler($attachment)
  {
    $this->out('');
    $this->out('upload complete : '.$attachment->name);
    $this->success = true;
  }

  public function errorHandler($attachment, $status, $error)
  {
    throw $error;
  }
}
