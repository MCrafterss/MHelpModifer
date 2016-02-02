<?php
namespace MCrafters\HelpModifer\command;

use MCrafters\HelpModifer\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ModifedHelpCommand extends Command{
    /** 
     *@var Main 
     */

    private $plugin;
    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        parent::__construct("help", "gives you help", null, ["?"]);
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param int $height
     * @param int $page
     */
    public function sendHelp(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("messages") as $msg){
            $sender->sendMessage($this->replaceStrings($msg, $sender));
        }
    }
    
    /**
     * @param string $msg
     * @param CommandSender $sender
     * @return string
     */
    public function replaceStrings($msg, CommandSender $sender){
        $msg = str_replace("&", "§", $msg);
        $msg = str_replace("{name}", $sender->getName(), $msg);
        $msg = str_replace("{maxplayers}", count($this->plugin->getServer()->getMaxPlayers()), $msg);
        $msg = str_replace("{playercount}", count($this->plugin->getServer()->getOnlinePlayers()), $msg);
        return $msg;
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param string[] $args
     * @return bool
     */
    
    public function execute(CommandSender $sender, $label, array $args){
        $this->sendHelp($sender);
        return true;
    }
}
