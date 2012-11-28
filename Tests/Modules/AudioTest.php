<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class AudioTest extends \PHPUnit_Framework_TestCase
{
    protected static $mp3File;
    protected static $wavFile;
    protected static $class;

    protected function setUp()
    {
        self::$mp3File = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'mp3demo.mp3';
        self::$wavFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'Yamaha-SY35-Buzzy-Synth-Lead-C4.wav';
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

    public function testMp3File()
    {
        $this->assertFileExists(self::$mp3File);
        $this->assertTrue(is_readable(self::$mp3File));
    }

    /**
     * @depends testClass
     * @depends testMp3File
     */
    public function testReadMp3()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $audio = $getId3->analyze(self::$mp3File);
        $this->assertArrayNotHasKey('error', $audio);
        $this->assertArrayHasKey('audio', $audio);
        $this->assertArrayHasKey('dataformat', $audio['audio']);
        $this->assertEquals('mp3', $audio['audio']['dataformat']);
    }

    public function testWavFile()
    {
        $this->assertFileExists(self::$wavFile);
        $this->assertTrue(is_readable(self::$wavFile));
    }

    /**
     * @depends testClass
     * @depends testWavFile
     */
    public function testReadWav()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $audio = $getId3->analyze(self::$wavFile);
        $this->assertArrayNotHasKey('error', $audio);
        $this->assertArrayHasKey('audio', $audio);
        $this->assertArrayHasKey('dataformat', $audio['audio']);
        $this->assertEquals('wav', $audio['audio']['dataformat']);
        $this->assertArrayHasKey('codec', $audio['audio']);
        $this->assertEquals('Pulse Code Modulation (PCM)', $audio['audio']['codec']);
        $this->assertArrayHasKey('bitrate', $audio['audio']);
        $this->assertSame(1411200, $audio['audio']['bitrate']);
    }
}
