<?php
namespace Attachment\ThirdParty\Mover;

use Attachment\Model\Entity\Attachment;
use Attachment\Fly\Profile;
use Attachment\ThirdParty\Http\Google\Client; // Wrap of google's Google_Client class
use Google_Service_YouTube;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;

// inspired by https://www.codexworld.com/upload-video-to-youtube-using-php/

// composer require google/apiclient:^2.0
// + .gitignore add: /config/google/*
// + select/create a Google prject https://console.developers.google.com/apis/dashboard
// + active Youtube Library in library menu
// + create and download a OAuth Client type Other, place it as config/google/credentials.json
class YoutubeMover extends BaseMover
{
  public $client;

  public $service;

  protected $_defaultConfig = [
    'client' => [
      'application_name' => 'Attachment\ThirdParty\Mover\YoutubeMover',
    ],
    'privacy' => 'public',
    'category' => null, // Numeric video category. See https://developers.google.com/youtube/v3/docs/videoCategories/list
    'chunkSize' => 1 * 1024 * 1024
  ];

  public function __construct($config = [])
  {
    parent::__construct($config);
    $this->client = (new Client($this->getConfig('client')))->enableCliOAuth([
      Google_Service_YouTube::YOUTUBE,
      Google_Service_YouTube::YOUTUBE_FORCE_SSL,
      Google_Service_YouTube::YOUTUBE_READONLY,
      Google_Service_YouTube::YOUTUBE_UPLOAD
    ]);
    $this->service = new Google_Service_YouTube($this->client);
  }

  public function createVideo(Attachment $attachment)
  {
    // snippet
    $snippet = new Google_Service_YouTube_VideoSnippet();
    $snippet->setTitle($attachment->title? $attachment->title: $attachment->name);
    if($attachment->description) $snippet->setDescription($attachment->description);
    if($this->getConfig('category')) $snippet->setCategoryId($this->getConfig('category'));
    if($attachment->atags)
    {
      $tags = [];
      foreach($attachment->atags as $atag) $tags[] = $atag->name;
      $snippet->setTags($tags);
    }

    // status
    $status = new Google_Service_YouTube_VideoStatus();
    $status->privacyStatus = $this->getConfig('privacy');

    // video
    $video = new Google_Service_YouTube_Video();
    $video->setSnippet($snippet);
    $video->setStatus($status);

    return $video;
  }

  public function move(Attachment $attachment, $progressCb = null, $successCb = null, $errorCb = null)
  {
    // upload
    try {
      $status = $this->_upload($attachment, $progressCb);
    }
    catch (\Exception $e)
    {
      // catch error
      if($errorCb) $errorCb($attachment, $status, $e);
      else throw new \Exception($e->getMessage());
    }

    // success
    $this->attachment->set('type', 'embed');
    $this->attachment->set('subtype', 'video');
    $this->attachment->set('profile', '');
    $this->attachment->set('embed', '<embed width="100%" height="100%" src="https://www.youtube.com/embed/'.$status['id'].'"></embed>');
    if($successCb) $successCb($attachment);

    return $this;
  }

  protected function _upload(Attachment $attachment, $progressCb)
  {
    $filesystem = (new Profile($attachment->profile))->filesystem();
    $this->client->setDefer(true);
    $insertRequest = $this->service->videos->insert('status,snippet', $this->createVideo($attachment));

    // set upload
    $media = new Google_Http_MediaFileUpload($this->client,$insertRequest,'video/*',null,true,$this->getConfig('chunkSize'));
    $media->setFileSize($size = $filesystem->getSize($attachment->path));

    // Read the media file and upload it chunk by chunk.
    $status = false;
    $uploadedBytes = 0;
    $handle = $filesystem->readStream($attachment->path);
    while (!$status && !feof($handle))
    {
      $chunk = fread($handle, $this->getConfig('chunkSize'));
      $status = $media->nextChunk($chunk);
      $uploadedBytes += $this->getConfig('chunkSize');
      if($progressCb) $progressCb($attachment, $size, $uploadedBytes, $this->getConfig('chunkSize'));
    }
    $this->client->setDefer(false);

    return $status;
  }
}
