<?php

declare(strict_types=1);

namespace Tests\Integration;

use ReflectionClass;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Tests\Utils\InMemoryResultWriter;

abstract class IntegrationTest extends TestCase
{
    protected static $phpServerPort;

    /** @var Process */
    protected static $process;

    public static function setUpBeforeClass(): void
    {
        $phpBinary = (new PhpExecutableFinder())->find();
        $directory = static::getChildClassPath() . DIRECTORY_SEPARATOR . 'server';

        static::$process = new Process([$phpBinary, '-S', self::getHost(), '-t', "{$directory}"]);
        static::$process->start();

        usleep((int) $_ENV['TEST_SERVER_WAIT']); // Wait for the server to start

        if (static::$process->isRunning() === false) {
            throw new RuntimeException('Could not start PHP server: ' . static::$process->getErrorOutput());
        }
    }

    public static function tearDownAfterClass(): void
    {
        static::$process->stop();
    }

    public static function getHost()
    {
        return sprintf('127.0.0.1:%s', (string) $_ENV['TEST_SERVER_PORT']);
    }

    public static function getHostUrl()
    {
        return 'http://' . self::getHost();
    }

    protected function tearDown(): void
    {
        // Cleanup dangling results
        InMemoryResultWriter::$results = [];
    }

    protected static function getChildClassPath(): string
    {
        $reflector = new ReflectionClass(static::class);
        $filename = $reflector->getFileName();

        return dirname($filename);
    }
}
