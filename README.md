getID3
======

This library integrates the getID3 library with the Symfony2 project, emulating the PSR-0 CS.

For more info read the 
**Warning**: documentation files are not rendering correctly in Github (reStructuredText format)
and some content might be broken or hidden, make sure to read raw files.

* [Main site] http://www.getid3.org
* [Support] http://support.getid3.org/

License
-------

For license info please read (https://github.com/phansys/getID3/doc/license.txt)
For commercial license read (https://github.com/phansys/getID3/doc/license.commercial.txt)

## Installation

### Step 1: Download getID3

Add following lines to your `deps` file:

```
    [getID3]
        git=https://github.com/phansys/getID3.git
        target=/getID3

```
Now, run the vendors script to download the library:

``` bash
$ php bin/vendors install
```

### Step 2: Configure the Autoloader

Add the `getID3` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerPrefixes(array(
    // ...
        'getID3_' => __DIR__.'/../vendor/getID3/lib',
        ));
```

Quick use example reading audio properties
------------------------------------------

``` php
<?php
namespace My\Bundle\Entity;

use \getID3_getID3;

class Audio
{
    // ...
    private function MyFunc()
    {
        $getID3 = new getID3_getID3();
        $getID3->option_md5_data        = true;
        $getID3->option_md5_data_source = true;
        $getID3->encoding               = 'UTF-8';		
        //$this->file: instance of Symfony\Component\HttpFoundation\File\UploadedFile or any valid file resource
        $audio = $getID3->analyze($this->file);			
        if (isset($audio['error'])) 
        {
            throw new \RuntimeException('Error at reading audio properties with getID3_getID3 : ' . $this->file);
        }			
        $this->setLength(isset($audio['playtime_seconds']) ? $audio['playtime_seconds'] : '');
    }
}
```