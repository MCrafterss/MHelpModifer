<?php

namespace MCrafters\HelpModifer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use MCrafters\HelpModifer\command\ModifedHelpCommand;

class Main extends PluginBase implements Listener {
	
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info("§l§cHelp§6Modifer §aEnabled§c!");
		$this->getServer()->getCommandMap()->getCommand("help")->setLabel("help_disabled");
		$this->getServer()->getCommandMap()->getCommand("help")->unregister($this->getServer()->getCommandMap());
		$this->getServer()->getCommandMap()->register("help", new ModifedHelpCommand($this));

	}
}
