<?php
namespace Attachment\ThirdParty\Shell\Task;
use Attachment\Fly\Profile;

class QueueMoveFileAndUpdateTask extends QueueMoveFileTask
{

  public function add()
  {
    $this->err('!!! Queue Move File And Update cannot be added via Console !!!');
    $this->hr();
  }

  public function run(array $data, $id)
  {
    if(!$this->success = parent::run($data, $id)) return false;

    // set profile
    $profile = new Profile($this->attachment->profile);

    // test if remove file
    switch($this->attachment->type)
    {
      case 'youutbe':
      default:

      // deletes only if Profile specified it
      if(!$profile->deleteExisting) break;
      //$profile->delete($this->attachment->profile);
      $this->Attachments->patchEntity($this->attachment,[
        'embed' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$this->attachment->mover['id'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
      ]);

      //debug($this->attachment);
    }

    // update and save $this->attachment
    if(!$result = $this->Attachments->save($this->attachment))
    {
      $this->failureMessage = 'Unable to save : '.$this->attachment->name.":\n".var_dump($this->attachment->getErrors());
      $this->err($this->failureMessage);
      $this->success = false;
    }

    //return result
    return $this->success;
  }
}
