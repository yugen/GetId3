<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class GraphicTest extends \PHPUnit_Framework_TestCase
{
    protected static $jpgFile;
    protected static $pngFile;
    protected static $class;

    protected function setUp()
    {
        self::$jpgFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'jpgsample.jpg';
        self::$pngFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'pngsample.png';
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
    
    public function testPngFile()
    {
        $this->assertFileExists(self::$pngFile);
        $this->assertTrue(is_readable(self::$pngFile));
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
    
    /**
     * @depends testClass
     * @depends testPngFile
     */
    public function testReadPng()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $image = $getId3->analyze(self::$pngFile);
        $this->assertArrayNotHasKey('error', $image);
        $this->assertArrayHasKey('mime_type', $image);
        $this->assertEquals('image/png', $image['mime_type']);
        $this->assertArrayHasKey('fileformat', $image);
        $this->assertEquals('png', $image['fileformat']);
        $this->assertArrayHasKey('video', $image);        
        $this->assertArrayHasKey('dataformat', $image['video']);
        $this->assertEquals('png', $image['video']['dataformat']);
        $this->assertArrayHasKey('lossless', $image['video']);
        $this->assertArrayHasKey('bits_per_sample', $image['video']);
        $this->assertArrayHasKey('resolution_x', $image['video']);
        $this->assertSame(300, $image['video']['resolution_x']);
        $this->assertArrayHasKey('resolution_y', $image['video']);
        $this->assertSame(225, $image['video']['resolution_y']);
        $this->assertArrayHasKey('compression_ratio', $image['video']);
        $this->assertSame(0.17746296296296, $image['video']['compression_ratio']);
        $this->assertArrayHasKey('png', $image);  
        $this->assertArrayHasKey('IHDR', $image['png']); 
        $this->assertArrayHasKey('width', $image['png']['IHDR']);
        $this->assertSame(300, $image['png']['IHDR']['width']);
        $this->assertArrayHasKey('height', $image['png']['IHDR']);
        $this->assertSame(225, $image['png']['IHDR']['height']);
        $this->assertArrayHasKey('compression_method_text', $image['png']['IHDR']);
        $this->assertEquals('deflate/inflate', $image['png']['IHDR']['compression_method_text']);
        $this->assertArrayHasKey('bKGD', $image['png']);
        $this->assertArrayHasKey('background_red', $image['png']['bKGD']);
        $this->assertSame(1095233372415, $image['png']['bKGD']['background_red']);
    }
}
