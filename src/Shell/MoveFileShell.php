<?php
namespace Attachment\ThirdParty\Shell;

use Cake\Console\Shell;

class MoveFileShell extends Shell
{
  public function main()
  {
    $this->loadModel('Attachments');
    $attachments = $this->Attachments->find()
    ->where([
      'type' => 'transit'
    ])
    ->limit(1)
    ->toArray();

    debug($attachments);
  }
}
