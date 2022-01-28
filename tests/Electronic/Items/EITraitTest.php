<?php

use PHPUnit\Framework\TestCase;
use Shop\Electronic\Items\EIConsole;
use Shop\Electronic\Items\EIController;
use Shop\Electronic\Items\EIMicrowave;
use Shop\Electronic\Items\EITelevision;

final class EITraitTest extends TestCase
{
    
    public function testEIConsoleMaxExtras(): void
    {
        $electronicItem = new EIConsole(123);
        
        for($i=1; $i<=EIConsole::MAX_EXTRA; $i++){
            $electronicItem->addExtra(new EIController(12, false));
            $this->assertCount($i, $electronicItem->getExtras()->getItemsByType());
        }
        
        $this->expectException(Exception::class);
        $electronicItem->addExtra(new EIController(12, false));
    }
    
    public function testEIControllerMaxExtras(): void
    {
        $electronicItem = new EIController(123);
        
        for($i=1; $i<=EIController::MAX_EXTRA; $i++){
            $electronicItem->addExtra(new EIController(12, false));
            $this->assertCount($i, $electronicItem->getExtras()->getItemsByType());
        }
        
        $this->expectException(Exception::class);
        $electronicItem->addExtra(new EIController(12, false));
    }
    
    public function testEIMicrowaveMaxExtras(): void
    {
        $electronicItem = new EIMicrowave(123);
        
        for($i=1; $i<=EIMicrowave::MAX_EXTRA; $i++){
            $electronicItem->addExtra(new EIController(12, false));
            $this->assertCount($i, $electronicItem->getExtras()->getItemsByType());
        }
        
        $this->expectException(Exception::class);
        $electronicItem->addExtra(new EIController(12, false));
    }
    
    public function testEITelevisionMaxExtras(): void
    {
        $electronicItem = new EITelevision(123);
        
        for($i=1; $i<=20; $i++){
            $electronicItem->addExtra(new EIController(12, false));
            $this->assertCount($i, $electronicItem->getExtras()->getItemsByType());
        }        
    }
    
}