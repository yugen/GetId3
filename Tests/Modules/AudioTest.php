<?php

namespace GetId3\Tests\Modules;

use GetId3\GetId3Core;

class AudioTest extends \PHPUnit_Framework_TestCase
{
    protected static $mp3File;
    protected static $wavFile;
    protected static $vqfFile;
    protected static $flacFile;
    protected static $oggFile;
    protected static $class;

    protected function setUp()
    {
        self::$mp3File = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'mp3demo.mp3';
        self::$wavFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'Yamaha-SY35-Buzzy-Synth-Lead-C4.wav';
        self::$vqfFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'vqfsample.vqf';
        self::$flacFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'flacsample.flac';
        self::$oggFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'oggsample.ogg';
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

    public function testVqfFile()
    {
        $this->assertFileExists(self::$vqfFile);
        $this->assertTrue(is_readable(self::$vqfFile));
    }

    /**
     * @depends testClass
     * @depends testVqfFile
     */
    public function testReadVqf()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $audio = $getId3->analyze(self::$vqfFile);
        $this->assertArrayNotHasKey('error', $audio);
        $this->assertArrayNotHasKey('warning', $audio);
        $this->assertArrayHasKey('fileformat', $audio);
        $this->assertEquals('vqf', $audio['fileformat']);
        $this->assertArrayHasKey('audio', $audio);
        $this->assertArrayHasKey('dataformat', $audio['audio']);
        $this->assertEquals('vqf', $audio['audio']['dataformat']);
        $this->assertArrayHasKey('bitrate_mode', $audio['audio']);
        $this->assertArrayHasKey('encoder_options', $audio['audio']);
        $this->assertEquals('CBR48', $audio['audio']['encoder_options']);
        $this->assertArrayHasKey('compression_ratio', $audio['audio']);
        $this->assertSame(0.068027210884354, $audio['audio']['compression_ratio']);
        $this->assertArrayHasKey('tags', $audio);
        $this->assertArrayHasKey('vqf', $audio['tags']);
        $this->assertArrayHasKey('title', $audio['tags']['vqf']);
        $this->assertArrayHasKey('mime_type', $audio);
        $this->assertEquals('application/octet-stream', $audio['mime_type']);
        $this->assertArrayHasKey('vqf', $audio);
        $this->assertArrayHasKey('raw', $audio['vqf']);
        $this->assertArrayHasKey('playtime_seconds', $audio);
        $this->assertSame(178.553, $audio['playtime_seconds']);
    }

    public function testFlacFile()
    {
        $this->assertFileExists(self::$flacFile);
        $this->assertTrue(is_readable(self::$flacFile));
    }

    /**
     * @depends testClass
     * @depends testFlacFile
     */
    public function testReadFlac()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $audio = $getId3->analyze(self::$flacFile);
        $this->assertArrayNotHasKey('error', $audio);
        $this->assertArrayNotHasKey('warning', $audio);
        $this->assertArrayHasKey('fileformat', $audio);
        $this->assertEquals('flac', $audio['fileformat']);
        $this->assertArrayHasKey('audio', $audio);
        $this->assertArrayHasKey('dataformat', $audio['audio']);
        $this->assertEquals('flac', $audio['audio']['dataformat']);
        $this->assertArrayHasKey('bitrate_mode', $audio['audio']);
        $this->assertArrayHasKey('encoder', $audio['audio']);
        $this->assertEquals('libFLAC 1.1.4 20070213', $audio['audio']['encoder']);
        $this->assertArrayHasKey('compression_ratio', $audio['audio']);
        $this->assertSame(0.14657823129252, $audio['audio']['compression_ratio']);
        $this->assertArrayHasKey('mime_type', $audio);
        $this->assertEquals('audio/x-flac', $audio['mime_type']);
        $this->assertArrayHasKey('flac', $audio);
        $this->assertArrayHasKey('STREAMINFO', $audio['flac']);
        $this->assertArrayHasKey('samples_stream', $audio['flac']['STREAMINFO']);
        $this->assertSame(220500, $audio['flac']['STREAMINFO']['samples_stream']);
        $this->assertArrayHasKey('playtime_seconds', $audio);
        $this->assertSame(5, $audio['playtime_seconds']);
        $this->assertArrayHasKey('compression_ratio', $audio['flac']);
        $this->assertSame(0.13716326530612, $audio['flac']['compression_ratio']);
    }
    
    public function testOggFile()
    {
        $this->assertFileExists(self::$oggFile);
        $this->assertTrue(is_readable(self::$oggFile));
    }

    /**
     * @depends testClass
     * @depends testOggFile
     */
    public function testReadOgg()
    {
        $getId3 = new GetId3Core();
        $getId3->option_md5_data        = true;
        $getId3->option_md5_data_source = true;
        $getId3->encoding               = 'UTF-8';
        $audio = $getId3->analyze(self::$oggFile);
        $this->assertArrayNotHasKey('error', $audio);
        $this->assertArrayNotHasKey('warning', $audio);
        $this->assertArrayHasKey('fileformat', $audio);
        $this->assertEquals('ogg', $audio['fileformat']);
        $this->assertArrayHasKey('audio', $audio);
        $this->assertArrayHasKey('dataformat', $audio['audio']);
        $this->assertEquals('vorbis', $audio['audio']['dataformat']);
        $this->assertArrayHasKey('bitrate_mode', $audio['audio']);
        $this->assertEquals('vbr', $audio['audio']['bitrate_mode']);
        $this->assertArrayHasKey('encoder', $audio['audio']);
        $this->assertEquals('Xiph.Org libVorbis I 20030909', $audio['audio']['encoder']);
        $this->assertArrayHasKey('encoder_options', $audio['audio']);
        $this->assertArrayHasKey('compression_ratio', $audio['audio']);
        $this->assertSame(0.075160635578802, $audio['audio']['compression_ratio']);
        $this->assertArrayHasKey('mime_type', $audio);
        $this->assertEquals('application/ogg', $audio['mime_type']);
        $this->assertArrayHasKey('ogg', $audio);
        $this->assertArrayHasKey('bitrate_average', $audio['ogg']);
        $this->assertArrayHasKey('playtime_seconds', $audio);
        $this->assertSame(28.656009070295, $audio['playtime_seconds']);
    }
}
