<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class AudioVideoTest extends \PHPUnit_Framework_TestCase
{
    protected static $quicktimeFile;
    protected static $class;

    protected function setUp()
    {
        self::$quicktimeFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'sample_iTunes.mov';
        self::$class = 'GetId3\\GetId3Core';

        if (!class_exists(self::$class)) {
            $this->markTestSkipped(self::$class . ' is not available.');
        }
        $this->assertTrue(class_exists(self::$class));
        $rc = new \ReflectionClass(self::$class);
        $this->assertTrue($rc->hasProperty('option_md5_data') && $rc->hasProperty('option_md5_data_source') && $rc->hasProperty('encoding'));
        $this->assertTrue($rc->hasMethod('analyze'));
        $rm = new \ReflectionMethod(self::$class, 'analyze');
        $this->assertTrue($rm->isPublic());
    }

    public function testFile()
    {
        $this->assertTrue(file_exists(self::$quicktimeFile) && is_readable(self::$quicktimeFile));
    }

    public function testReadQuicktime()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $properties = $getId3->analyze(self::$quicktimeFile);        
        $this->assertTrue(isset($properties['mime_type']) && $properties['mime_type'] == 'video/quicktime');
        $this->assertTrue(isset($properties['encoding']) && $properties['encoding'] == 'UTF-8');
        $this->assertTrue(isset($properties['filesize']) && $properties['filesize'] == 3284257);
    }        
}
