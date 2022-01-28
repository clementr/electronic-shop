<?php

use PHPUnit\Framework\TestCase;
use Shop\Electronic\ElectronicCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class ElectronicCommandTest extends TestCase
{
    private $commandTester;
    
    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new ElectronicCommand());
        $command = $application->find('shop:electronic');
        $this->commandTester = new CommandTester($command);
    }
    
    protected function tearDown(): void
    {
        $this->commandTester = null;
    }
    
    public function testQuestionArg(): void
    {
        $exitCode = $this->commandTester->execute(['question' => 'question1']);
        $this->assertEquals(0, $exitCode);
        
        $exitCode = $this->commandTester->execute(['question' => 'question2']);
        $this->assertEquals(0, $exitCode);
        
        $this->expectException(Exception::class);
        $this->commandTester->execute(['question' => 'question3']);
    }
}