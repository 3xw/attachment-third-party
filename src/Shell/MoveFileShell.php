<?php
namespace Attachment\ThirdParty\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Attachment\Fly\Profile;

class MoveFileShell extends Shell
{
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
      $mover->move($attachment);
    }
  }
}
