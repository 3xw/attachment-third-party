# attachment-third-party plugin for CakePHP
Attachment third pary ( Youtube etc ... ). Is a sub project of plugin 3xw/attachment

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```bash
composer require 3xw/attachment-third-party
```

### Add Queue tasks
Acrtive the plugin in your Application.php file:

```php
// Queue
$this->addPlugin('Queue');
```

Then migrate the db

```bash
bin/cake migrations migrate -p Queue
```

## Google
### Install
Install Google lib:

```bash
composer require google/apiclient:^2.0
```

### Protect
Ignore your Google private settings. Add following in .gitignore file:

```bash
/config/google/*
```

### OAuth
- select/create a Google project [here](https://console.developers.google.com/apis/dashboard)
- active Youtube Library in library menu
- create and download a OAuth Client (of type "Other"), place it as config/google/credentials.json

## Usage
### Prepare
When using attachment, setup an event listener either:
- globally in your config/attachment.php

```php
return [
	'Attachment' => [

		//...

		'listeners' => [
			'beforeSave' => [
				'Attachment\Listener\ModifyTypeListener' => [
					'type' => 'transit',
					'subtype' => 'youtube', // or other third party name
				]
			],
		]

		//...

	]
];
```
- specifically in any template:

```php
$this->Attachment->buildIndex([
	'actions' => ['add','edit','delete','view'],
	'types' =>['image/jpeg','image/png','embed/youtube','embed/vimeo','video/quicktime','transit/youtube'],
	'listeners' => [
		'beforeSave' => [
			'Attachment\Listener\ModifyTypeListener' => [
				'type' => 'transit',
				'subtype' => 'youtube',
			]
		],
	]
])
```

### Move Files
By creating a cron tasks, the plugin will look for attachments with a type "transit" and create Queue tasks for each attachment with relevant Mover Class.

```bash
bin/cake queue add CreateTransitStack & bin/cake queue runworker
```
