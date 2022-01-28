<?php

namespace Shop\Electronic;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ElectronicCommand extends Command
{   
    protected function configure()
    {
        $this->setName('shop:electronic')
            ->addArgument('question', InputArgument::REQUIRED, 'Question ref. is requires (question1|question2)')
            ->setDescription('Question 1: Order for 1 console, 2 Tv and 1 microwave. Question 2: Total for the console and its extras');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Check question validity
        if(!in_array($input->getArgument('question'), ['question1', 'question2'])){
            throw new \Exception(sprintf('Question «%s» is not a valid question', $input->getArgument('question')));
        }
        
        /**
        * Question 1
        * 
        * A person would buy:
        * - 1 console (with 2 wired controllers and 2 wireless controllers as extras)
        * - 2 televisions (with different prices) (TV #1 has 2 remote controllers and the TV #2 has 1 remote controller.)
        * - 1 microwave
        */
        
        //CONSOLE
        $wiredControllerConsole = new Items\EIController(25);
        $wiredControllerConsole2 = new Items\EIController(25);
        $wireless = new Items\EIController(39.99, false);
        $wireless2 = new Items\EIController(39.99, false);
        
        $console = new Items\EIConsole(
            549.99, 
            true, 
            [
                $wiredControllerConsole, 
                $wiredControllerConsole2, 
                $wireless, 
                $wireless2
            ]
        );
        
        //TV #1 (2 remote controllers)
        $television1 = new Items\EITelevision(379);
        $television1->addExtra(new Items\EIController(17, false));
        $television1->addExtra(new Items\EIController(17, false));
        
        //TV #2 (1 remote controller)
        $television2 = new Items\EITelevision(399.55);
        $television2->addExtra(new Items\EIController(17, false));
        
        //MICROWAVE #1
        $microwave = new Items\EIMicrowave(145.85);
        
        //Question #1
        if($input->getArgument('question') == 'question1'){
            $output->writeln("=================Electronic Q1=================\n");
            $output->writeln("The console and televisions have extras; those extras are controllers. \nThe console has 2 remote controllers and 2 wired controllers. The TV #1 has 2 remote controllers and the TV #2 has 1remote controller. \nSort the items by price and output the total pricing.\n");
            
            //Setup the list of electro items for Question #1
            $electronicItems = new ElectronicItems(
                [
                    $console, 
                    $television1, 
                    $television2, 
                    $microwave
                ]
            );
            
            $this->renderOrder($output, $electronicItems);
            
            return 0;
        }
        
        //Question #2
        if($input->getArgument('question') == 'question2'){
            $output->writeln("=================Electronic Q2=================\n");
            $output->writeln("That person's friend saw her with her new purchase and asked her how much the
console and its controllers had cost her. Give the answer.\n");
            
            
            //Setup the list of electro items for Question #1
            $electronicItems = new ElectronicItems(
                [
                    $console
                ]
            );
            
            $this->renderOrder($output, $electronicItems);
            
            return 0;
        }
    }
    
    /**
     * Render an orders composed of 1 or multiple elctronic items
     * 
     * @param OutputInterface $output
     * @param ElectronicItems $electronicItems
     */
    protected  function renderOrder(OutputInterface $output, ElectronicItems $electronicItems)
    {
        //FORMAT OUTPUT
        $table = new Table($output);
        
        $table->setHeaders(['Electronic Item', 'Wired', 'Price']);
        
        foreach($electronicItems->getSortedItems() as $electronicItem){
            $table->addRow([
                $electronicItem->getType(), 
                $this->getWireLabel($electronicItem->getWired()), 
                $electronicItem->getPrice()
            ]);
            
            /*
             * NOTE: Althgouh the total amount returned support extras recusively, I did not 
             * implement it in display because at the moment only controllers are considered as extras 
             * and they do not have extra themselves. (ie: Could be useful if we want to add batteries as controllers extras)
             */
            if($electronicItem->hasExtras()){                
                foreach($electronicItem->getExtras()->getSortedItems() as $extraElectronicItem){
                    $table->addRow([
                        '  Extra: '.$extraElectronicItem->getType(), 
                        $this->getWireLabel($extraElectronicItem->getWired()), 
                        $extraElectronicItem->getPrice()
                    ]);
                }
            }
        }
        
        $table->addRow(new TableSeparator());
        
        $table->addRow(
            [
                new TableCell('TOTAL', ['colspan' => 2]), 
                $electronicItems->getTotal()
            ]
        );
        
        $table->render();
    }

    protected function getWireLabel(bool $wired): string
    {
        return $wired ? 'wired' : 'wireless';
    }
}