<?php

namespace MCrafters\HelpModifer\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\event\TranslationContainer;
use MCrafters\HelpModifer\Main;

class ModifedHelpCommand extends Command implements \pocketmine\command\PluginIdentifiableCommand {

	/** @var Main $plugin */
   	private $plugin;
   	/** @var String[] $book */
   	protected $book = [];

	public function __construct(Main $plugin){
		parent::__construct("help", "Gives you help", null, ["?"]);
		$this->plugin = $plugin;
		$this->setPermission("mhelpmodifer.command.help");
		
		$commands = $this->plugin->getConfig()->get('content');
		if(!$commands){
			$plugin->getLogger()->warning("Failed to load help messages.");
			return;
		}
		
		$iperPage = 5;
		$page = 1;
		$cmds = [];
		$i = 1;
		foreach($commands as $k => $cmd){
			$cmds[$page][] = $cmd;
			if($i >= $iperPage){
				$i = 1;
				$page++;
			}
			$i++;
		}
		$this->book = $cmds;
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){	return true;	}
		
		$pgNum = 1;
		if(isset($args[0])){
			$pgNum = is_numeric($args[0]) ? intval($args[0]) : 1;
			if($pgNum <= 0) $pgNum = 1;
			if($pgNum > ($r = count($this->book))) $pgNum = $r;
		}

			$this->sendHelpPage($sender, $sender instanceof Player ? $pageNum : -1);
		return true;
	}
	
	public function sendHelpPage(CommandSender $issuer, $page = 1) : bool {
		$pg = [];
		if($page < 0){
			for($i=1;$i<=count($this->book);$i++){
				foreach($this->book[$i] as $line){
					$pg[] = $line;
				}
			}
		} elseif (isset($this->book[$page])) {
			$pg = $this->book[$page];
		}
		$issuer->sendMessage(new TranslationContainer("commands.help.header", [$page < 0 ? 1 : $page, $page < 0 ? 1 : \count($pg)]));
		foreach($pg as $line){
			$issuer->sendMessage($this->parseVars($line, $issuer));
		}
		return true;
	}
	
	public function parseVars($message, CommandSender $sender) : string {
	        $level = $sender instanceof Player ? $sender->getLevel() : $this->plugin->getServer()->getDefaultLevel();
	        $replace = ["&", "{maxplayers}", "{playercount}", "{name}", "{playerlevel}", "{playerlevelcount}"];
	        $with = ["§", $this->plugin->getServer()->getMaxPlayers(), count($this->plugin->getServer()->getOnlinePlayers()), $sender instanceof Player ? $sender->getName() : "CONSOLE", $level->getName(), count($level->getPlayers())];
	        $message = str_replace($replace, $with, $message);
	        return $message;
    }

    public function getPlugin(){
    	return $this->plugin;
    }

}
