<?php

namespace MCrafters\HelpModifer;

/*
 * __  __  ____            __ _                
 *|  \/  |/ ___|_ __ __ _ / _| |_ ___ _ __ ___ 
 *| |\/| | |   | '__/ _` | |_| __/ _ \ '__/ __|
 *| |  | | |___| | | (_| |  _| ||  __/ |  \__ \
 *|_|  |_|\____|_|  \__,_|_|  \__\___|_|  |___/
 *
*/

use pocketmine\plugin\PluginBase;
use MCrafters\HelpModifer\command\ModifedHelpCommand;

class Main extends PluginBase {
	
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getServer()->getLogger()->info("§l§cHelp§6Modifer §aEnabled§c!");
		$this->getServer()->getCommandMap()->getCommand("help")->setLabel("help_disabled");
		$this->getServer()->getCommandMap()->getCommand("help")->unregister($this->getServer()->getCommandMap());
		$this->getServer()->getCommandMap()->register("help", new ModifedHelpCommand($this));

	}
}
