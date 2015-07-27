bugsnag-php-symfony
===================

__*Important:*__ This bundle is not maintained anymore, since we don't use it ourselfs anymore. You probably shouldn't use this in a new project!

An integration bundle for the bugsnag-php module into the Symfony2 framework.

## Installation Instructions

### Step 1: Download the BugsnagBundle using composer

The best way to install the bundle is by using [Composer](http://getcomposer.org). Execute the following command:

`composer require wrep/bugsnag-php-symfony`

### Step 2: Include the bundle in your AppKernel

*app/AppKernel.php*

``` php
public function registerBundles()
{
    $bundles = array(
        // System Bundles
        ...
        new Wrep\Bundle\BugsnagBundle\BugsnagBundle(),
        ...
    );
}
```

### Step 3: Configuration

*app/config/config.yml*

```
bugsnag:
    api_key: your_api_key
    notify_stages: [ production ]
```

The `notify_stages` setting is optional. Default value is `production`.

### Step 4 (optional): Reporting errors from custom commands

By default, this bundle does not handle errors and exceptions that are raised from custom commands. You need to update the `app/console` file and make sure to call the commands using `--env=prod`.

#### Altering the `console` file

*app/console*

``` php
#!/usr/bin/env php
<?php

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

set_time_limit(0);

require_once __DIR__.'/bootstrap.php.cache';
require_once __DIR__.'/AppKernel.php';

use Wrep\Bundle\BugsnagBundle\Console\BugsnagConsoleApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;

$input = new ArgvInput();
$env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');
$debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', '')) && $env !== 'prod';

if ($debug) {
    Debug::enable();
}

$kernel = new AppKernel($env, $debug);
$application = new BugsnagConsoleApplication($kernel);
$application->run($input);
```

#### Make sure you use `--env=prod` when calling commands

Do make sure you are using `--env=prod` when executing commands on your server. Symfony executes commands in dev mode by default, and by default we skip errors and exceptions in debug mode.

`php app/console bundle:generate:something --env=prod`

## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
    
## About

See also the list of [contributors](https://github.com/Wrep/bugsnag-php-symfony/contributors).

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/wrep/bugsnag-php-symfony/issues). You're very welcome to submit issues or submit a pull request!
