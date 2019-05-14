<?php
namespace Attachment\ThirdParty\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

class MoveFileShell extends Shell
{
  public function main()
  {
    $this->loadModel('Attachment.Attachments');
    $attachments = $this->Attachments->find()
    ->where([
      'type' => 'transit'
    ])
    ->limit(1)
    ->toArray();

    $class = 'Attachment\ThirdParty\Mover\YoutubeMover';
    $mover = new $class([
      'client' => [
        'client_id' => Configure::read('Api.Google.client.id'),
        'client_secret' => Configure::read('Api.Google.client.secret'),
        'developer_key' => Configure::read('Api.Google.key'),
        'redirect_uri' => null,
        'state' => null,
      ]
    ]);

    foreach($attachments as $attachment)
    {
      $mover->move($attachment);
    }
  }
}
