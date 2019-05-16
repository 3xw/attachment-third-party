# attachment-third-party plugin for CakePHP
Attachment third pary ( Youtube etc ... )

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
