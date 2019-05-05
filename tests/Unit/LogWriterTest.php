<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Support\LogWriter;
use Tests\Utils\InMemoryLogWriter;

class LogWriterTest extends TestCase
{
    public function testLoggerDebugVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::DEBUG);

        $this->assertCount(8, InMemoryLogWriter::$log);
        $this->assertEquals('[DEBUG] Debug message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[INFO] Info message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[NOTICE] Notice message', InMemoryLogWriter::$log[2]);
        $this->assertEquals('[WARNING] Warning message', InMemoryLogWriter::$log[3]);
        $this->assertEquals('[ERROR] Error message', InMemoryLogWriter::$log[4]);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[5]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[6]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[7]);
    }

    public function testLoggerInfoVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::INFO);

        $this->assertCount(7, InMemoryLogWriter::$log);
        $this->assertEquals('[INFO] Info message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[NOTICE] Notice message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[WARNING] Warning message', InMemoryLogWriter::$log[2]);
        $this->assertEquals('[ERROR] Error message', InMemoryLogWriter::$log[3]);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[4]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[5]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[6]);
    }

    public function testLoggerNoticeVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::NOTICE);

        $this->assertCount(6, InMemoryLogWriter::$log);
        $this->assertEquals('[NOTICE] Notice message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[WARNING] Warning message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[ERROR] Error message', InMemoryLogWriter::$log[2]);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[3]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[4]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[5]);
    }

    public function testLoggerWarningVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::WARNING);

        $this->assertCount(5, InMemoryLogWriter::$log);
        $this->assertEquals('[WARNING] Warning message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[ERROR] Error message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[2]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[3]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[4]);
    }

    public function testLoggerErrorVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::ERROR);

        $this->assertCount(4, InMemoryLogWriter::$log);
        $this->assertEquals('[ERROR] Error message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[2]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[3]);
    }

    public function testLoggerCriticalVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::CRITICAL);

        $this->assertCount(3, InMemoryLogWriter::$log);
        $this->assertEquals('[CRITICAL] Critical message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[1]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[2]);
    }

    public function testLoggerAlertVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::ALERT);

        $this->assertCount(2, InMemoryLogWriter::$log);
        $this->assertEquals('[ALERT] Alert message', InMemoryLogWriter::$log[0]);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[1]);
    }

    public function testLoggerEmergencyVerbosity(): void
    {
        $this->writeLogMessages(LogLevel::EMERGENCY);

        $this->assertCount(1, InMemoryLogWriter::$log);
        $this->assertEquals('[EMERGENCY] Emergency message', InMemoryLogWriter::$log[0]);
    }

    protected function tearDown(): void
    {
        InMemoryLogWriter::$log = [];
    }

    protected function writeLogMessages($verbosity)
    {
        /** @var Outputter $outputterMock Just to silence the warnings further down the line */
        $outputterMock = $this->getMockBuilder(Outputter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logWriter = new LogWriter([
            [
                'class' => new InMemoryLogWriter(),
                'verbosity' => $verbosity,
            ]
        ], $outputterMock);

        $logWriter->debug('Debug message');
        $logWriter->info('Info message');
        $logWriter->notice('Notice message');
        $logWriter->warning('Warning message');
        $logWriter->error('Error message');
        $logWriter->critical('Critical message');
        $logWriter->alert('Alert message');
        $logWriter->emergency('Emergency message');
    }
}
