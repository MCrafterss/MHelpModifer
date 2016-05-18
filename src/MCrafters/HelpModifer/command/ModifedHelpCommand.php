<?php

namespace MCrafters\HelpModifer\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\TranslationContainer;
use MCrafters\HelpModifer\Main;

class ModifedHelpCommand extends Command{

   private $plugin;

	public function __construct(Main $plugin){
		parent::__construct("help", "Gives you help", null, ["?"]);
		$this->plugin = $plugin;
		$this->setPermission("mhelpmodifer.command.help");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}
		$messages = $this->plugin->getConfig()->get("messages");

		if(\count($args) === 0){
			$command = "";
			$pageNumber = 1;
		}elseif(\is_numeric($args[\count($args) - 1])){
			$pageNumber = (int) \array_pop($args);
			if($pageNumber <= 0){
				$pageNumber = 1;
			}
			$command = \implode(" ", $args);
		}else{
			$command = \implode(" ", $args);
			$pageNumber = 1;
		}

		if($sender instanceof ConsoleCommandSender){
			$pageHeight = \PHP_INT_MAX;
		}else{
			$pageHeight = 5;
		}

		if($command === ""){
			$messages = \array_chunk($messages, $pageHeight);
			$pageNumber = (int) \min(\count($messages), $pageNumber);
			if($pageNumber < 1){
				$pageNumber = 1;
			}
			$sender->sendMessage(new TranslationContainer("commands.help.header", [$pageNumber, \count($messages)]));
			if(isset($messages[$pageNumber - 1])){
				foreach($messages[$pageNumber - 1] as $message){
					$sender->sendMessage($this->replaceStrings($message, $sender));
				}
			}

			return \true;
			
		}
	}
	
	public function replaceStrings($message, CommandSender $sender){
        $message = str_replace("&", "§", $message);
        $message = str_replace("{maxplayers}", count($this->plugin->getServer()->getMaxPlayers()), $message);
        $message = str_replace("{playercount}", count($this->plugin->getServer()->getOnlinePlayers()), $message);
        if($sender instanceof ConsoleCommandSender){
        $message = str_replace("{name}", "CONSOLE", $message);
        $message = str_replace("{playerlevel}", $sender->getServer()->getDefaultLevel()->getName(), $message);
        $message = str_replace("{playerlevelcount}", count($sender->getServer()->getDefaultLevel()->getPlayers()), $message);
          }else{
          $message = str_replace("{name}", $sender->getName(), $message);
          $message = str_replace("{playerlevel}", $sender->getLevel()->getName(), $message);
          $message = str_replace("{playerlevelcount}", count($sender->getLevel()->getPlayers()), $message);
         }
        return $message;
    }

}