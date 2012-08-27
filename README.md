GetId3
======
[![Build Status](https://secure.travis-ci.org/phansys/GetId3.png?branch=master)](http://travis-ci.org/phansys/GetId3)

This package integrates the GetId3 library with the Symfony2 project, emulating the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) CS.

**Warning**: documentation files are not rendering correctly in Github (reStructuredText format)
and some content might be broken or hidden, make sure to read raw files.

* [Main site] http://www.getid3.org
* [Support] http://support.getid3.org/

License
-------

For license info please read [doc/license.txt](https://github.com/phansys/GetId3/tree/master/doc/license.txt)

For commercial license read [doc/license.commercial.txt](https://github.com/phansys/GetId3/tree/master/doc/license.commercial.txt)

## Installation
(You can choose deps or composer install mechanisms)

### deps

##### Step 1: Download GetId3

Add following lines to your `deps` file:

```
[GetId3]
    git=https://github.com/phansys/GetId3.git
    target=/phansys/getid3/GetId3

```
Now, run the vendors script to download the library:

``` bash
$ php bin/vendors install
```

##### Step 2: Configure the Autoloader

Add the `GetId3` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerPrefixes(array(
    // ...
        'GetId3_' => __DIR__.'/../vendor/phansys/getid3/GetId3',
        ));
```
___

### [composer] (http://getcomposer.org/)

##### Step 1: Edit composer.json

Add following lines to your `composer.json` `"require"` definitions:

``` json
"phansys/getid3": "master"
```

##### Step 2: Run composer

Now, run the composer script to download the library:

``` bash
$ php composer.phar install
```


Quick use example reading audio properties
------------------------------------------

``` php
<?php
namespace My\Bundle\Entity;

use \GetId3_GetId3 as GetId3;

class Audio
{
    // ...
    private function MyFunc()
    {
        $GetId3 = new GetId3();
        $GetId3->option_md5_data        = true;
        $GetId3->option_md5_data_source = true;
        $GetId3->encoding               = 'UTF-8';		
        //$this->file: instance of Symfony\Component\HttpFoundation\File\UploadedFile or any valid file resource
        $audio = $GetId3->analyze($this->file);			
        if (isset($audio['error'])) 
        {
            throw new \RuntimeException('Error at reading audio properties with GetId3 : ' . $this->file);
        }			
        $this->setLength(isset($audio['playtime_seconds']) ? $audio['playtime_seconds'] : '');
    }
}
```
