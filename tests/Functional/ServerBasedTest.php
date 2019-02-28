<?php

namespace Tests\Functional;

use ReflectionClass;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

abstract class ServerBasedTest extends TestCase
{
    protected static $phpServerPort;

    /** @var Process */
    protected static $process;

    public static function setUpBeforeClass(): void
    {
        $phpBinary = (new PhpExecutableFinder())->find();
        self::$phpServerPort = $port = $_ENV['PHP_SERVER_PORT'];
        $directory = self::getChildClassPath() . DIRECTORY_SEPARATOR . 'server';

        self::$process = new Process([$phpBinary, '-S', "127.0.0.1:{$port}", '-t', "{$directory}"]);
        self::$process->start();

        usleep(500000); // Wait 0.5s for the server to start

        if (self::$process->isRunning() === false) {
            throw new RuntimeException('Could not start PHP server: ' . self::$process->getErrorOutput());
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$process->stop();
    }

    protected static function getChildClassPath(): string
    {
        $reflector = new ReflectionClass(static::class);
        $filename = $reflector->getFileName();

        return dirname($filename);
    }
}
