<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class GraphicTest extends \PHPUnit_Framework_TestCase
{
    protected static $jpgFile;
    protected static $class;

    protected function setUp()
    {
        self::$jpgFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'jpgsample.jpg';
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

    public function testJpgFile()
    {
        $this->assertFileExists(self::$jpgFile);
        $this->assertTrue(is_readable(self::$jpgFile));
    }

    /**
     * @depends testClass
     * @depends testJpgFile
     */
    public function testReadJpg()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $image = $getId3->analyze(self::$jpgFile);
        $this->assertArrayNotHasKey('error', $image);
        $this->assertArrayHasKey('mime_type', $image);
        $this->assertEquals('image/jpeg', $image['mime_type']);
        $this->assertArrayHasKey('fileformat', $image);
        $this->assertEquals('jpg', $image['fileformat']);
        $this->assertArrayHasKey('video', $image);        
        $this->assertArrayHasKey('dataformat', $image['video']);
        $this->assertEquals('jpg', $image['video']['dataformat']);
        $this->assertArrayHasKey('lossless', $image['video']);
        $this->assertArrayHasKey('bits_per_sample', $image['video']);
        $this->assertArrayHasKey('pixel_aspect_ratio', $image['video']);
        $this->assertArrayHasKey('resolution_x', $image['video']);
        $this->assertSame(313, $image['video']['resolution_x']);
        $this->assertArrayHasKey('resolution_y', $image['video']);
        $this->assertSame(234, $image['video']['resolution_y']);
        $this->assertArrayHasKey('compression_ratio', $image['video']);
        $this->assertSame(0.0069313599665037, $image['video']['compression_ratio']);
    }
}
