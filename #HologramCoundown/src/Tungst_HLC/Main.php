<?php

namespace Tungst_HLC;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
use Tungst_HLC\RefreshTask;
class Main extends PluginBase implements Listener {


	public function onEnable(){
		$this->getLogger()->info("Hologram countdown");
		@mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
		$a = new RefreshTask($this);
		$this->getScheduler()->scheduleRepeatingTask($a, 20);
		$this->getServer()->getPluginManager()->registerEvents($a,$this);
	}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
       switch (strtolower($command->getName())) {
	      case 'cd':
            if ($sender->isOp()) {
              if (empty($args[0])) {
                $sender->sendMessage("§e/cd <name> to create hologram");
                return false;
              }
              $name = $args[0];
              if (isset($this->getConfig()->getAll()["location"][$name])) {
                $sender->sendMessage("Already has this name");
                return false;
              }
              $contents =
                [
                  "name" => $name,
                  "x" => $sender->x,
                  "y" => $sender->y,
                  "z" => $sender->z,
                  "level" => $sender->getLevel()->getName(),
                ];
              $this->getConfig()->setNested("location.$name", $contents);
              $this->getConfig()->setAll($this->getConfig()->getAll());
              $this->getConfig()->save();
              $sender->sendMessage("§aSuccessfully created hologram $name.");
            }else{
              
            }

            break;	
	   }
	   return true;
	}	
}