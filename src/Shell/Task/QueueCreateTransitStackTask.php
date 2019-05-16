<?php
namespace Attachment\ThirdParty\Shell\Task;

use Queue\Shell\Task\QueueTask;

class QueueCreateTransitStackTask extends QueueTask {

  public function add()
  {
    $total = 0;

    // load attachments
    $this->loadModel('Attachment.Attachments');
    $attachments = $this->Attachments->find()
    ->contain(['Atags'])
    ->where(['type' => 'transit']);

    // for each create a task...
    foreach($attachments as $attachment)
    {
      switch($attachment->subtype)
      {
        case 'youutbe':
        default:
        $class = 'Attachment\ThirdParty\Mover\YoutubeMover';
      }

      // add Email Feedback
      $this->QueuedJobs->createJob('MoveFileAndUpdate', [
        'attachment' => $attachment,
        'mover' => $class
      ]);
      $this->out('-> New MoveFileAndUpdate for attachment: '.$attachment->name);
      $total += 1;
    }

    // Sumup
    $this->out(' ');
    $this->out('Total: '.$total.' tasks added.');
  }
}
