<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class ArchiveTest extends \PHPUnit_Framework_TestCase
{
    protected static $zipFile;
    protected static $class;

    protected function setUp()
    {
        self::$zipFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'zipsample.zip';
        self::$class = 'GetId3\\GetId3Core';
    }

    public function testClass()
    {
        if (!class_exists(self::$class)) {
            $this->markTestSkipped(self::$class . ' is not available.');
        }
        $this->assertTrue(class_exists(self::$class));
        $this->assertClassHasAttribute('option_md5_data', self::$class);
        $this->assertClassHasAttribute('option_md5_data_source', self::$class);
        $this->assertClassHasAttribute('encoding', self::$class);
        $rc = new \ReflectionClass(self::$class);
        $this->assertTrue($rc->hasMethod('analyze'));
        $rm = new \ReflectionMethod(self::$class, 'analyze');
        $this->assertTrue($rm->isPublic());
    }

    public function testZipFile()
    {
        $this->assertFileExists(self::$zipFile);
        $this->assertTrue(is_readable(self::$zipFile));
    }

    /**
     * @depends testClass
     * @depends testZipFile
     */
    public function testReadZip()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $archive = $getId3->analyze(self::$zipFile);
        $this->assertArrayNotHasKey('error', $archive);
        $this->assertArrayHasKey('mime_type', $archive);
        $this->assertEquals('application/zip', $archive['mime_type']);
        $this->assertArrayHasKey('zip', $archive);
        $this->assertArrayHasKey('fileformat', $archive);
        $this->assertEquals('zip', $archive['fileformat']);
        $this->assertArrayHasKey('encoding', $archive['zip']);
        $this->assertArrayHasKey('files', $archive['zip']);
        $this->assertArrayHasKey('entries_count', $archive['zip']);
    }
}
