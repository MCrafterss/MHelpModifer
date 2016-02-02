<?php
namespace MCrafters\HelpModifer\command;

use MCrafters\HelpModifer\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

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
     */
    public function sendHelp(CommandSender $sender, $height, $page){
        if($page <= 0){
            $page = 1;
        }

        $msgs = [];
        foreach($this->plugin->getConfig()->get("messages") as $msg){
            $msgs[$msg] = $this->replaceStrings($msg, $sender);
        }

        ksort($msgs, SORT_NATURAL | SORT_FLAG_CASE);
        $commands = array_chunk($msgs, $height);
        $page = (int) min(count($msgs), $page);

        if($page < 1) $page = 1;

        foreach($msgs[$page - 1] as $msg){
            $sender->sendMessage($msg);
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

        if($sender instanceof ConsoleCommandSender){
            $height = PHP_INT_MAX;
        }else{
            $height = 5;
        }
        if(isset($args[0])){
            if(is_numeric($args[0])){
                $this->sendHelp($sender, $height, $args[0]);
                return true;
            }
        }
        $this->sendHelp($sender, $height, 1);
    }
}
