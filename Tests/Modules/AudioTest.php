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

    public function testMp3File()
    {
        $this->assertTrue(file_exists(self::$mp3File) && is_readable(self::$mp3File));
    }

    public function testReadMp3()
    {
        $getId3 = new GetId3Core();
		$getId3->option_md5_data        = true;
		$getId3->option_md5_data_source = true;
		$getId3->encoding               = 'UTF-8';
		$audio = $getId3->analyze(self::$mp3File);
        $this->assertTrue(!isset($audio['error']));
        $this->assertTrue(isset($audio['audio']['dataformat']) && $audio['audio']['dataformat'] == 'mp3');
    }
    
    public function testWavFile()
    {
        $this->assertTrue(file_exists(self::$wavFile) && is_readable(self::$wavFile));
    }
    
    public function testReadWav()
    {
        $getId3 = new GetId3Core();
		$getId3->option_md5_data        = true;
		$getId3->option_md5_data_source = true;
		$getId3->encoding               = 'UTF-8';
		$audio = $getId3->analyze(self::$wavFile);
        $this->assertTrue(!isset($audio['error']));
        $this->assertTrue(isset($audio['audio']['dataformat']) && $audio['audio']['dataformat'] == 'wav');
        $this->assertTrue(isset($audio['audio']['codec']) && $audio['audio']['codec'] == 'Pulse Code Modulation (PCM)');
        $this->assertTrue(isset($audio['audio']['bitrate']) && $audio['audio']['bitrate'] == 1411200);
    }
}