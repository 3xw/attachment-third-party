<?php
namespace Attachment\ThirdParty\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Attachment\Fly\Profile;

class MoveFileShell extends Shell
{

  public $progress;

  public function main()
  {
    $this->loadModel('Attachment.Attachments');
    $attachments = $this->Attachments->find()
    ->contain(['Atags'])
    ->where([
      'type' => 'transit'
    ])
    ->limit(1)
    ->toArray();

    $class = 'Attachment\ThirdParty\Mover\YoutubeMover';
    $mover = new $class();

    foreach($attachments as $attachment)
    {
      // progress
      $this->out('processing : '.$attachment->name);
      $this->progress = $this->helper('Progress');
      $this->progress->init(['total' => $attachment->size,'width' => 0]);
      $this->progress->draw();

      // upload
      $mover->move($attachment, [$this, 'progressHandler'], [$this, 'successHandler'], [$this,'errorHandler']);
    }
  }

  public function progressHandler($attachment, $size, $uploadedBytes, $bytes)
  {
    $this->progress->increment($bytes);
    $this->progress->draw();
  }

  public function successHandler($attachment, $status)
  {
    $this->out('');
    $this->out('upload complete : '.$attachment->name);
    debug($status);
  }

  public function errorHandler($attachment, $status, $message)
  {
    $this->out('');
    debug($status);
    $this->err('error occured : '.$attachment->name.' '.$message);
  }
}
