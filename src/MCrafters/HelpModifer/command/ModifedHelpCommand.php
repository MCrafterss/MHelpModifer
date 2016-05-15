<?php

namespace MCrafters\HelpModifer\command;

/*
 * __  __  ____            __ _                
 *|  \/  |/ ___|_ __ __ _ / _| |_ ___ _ __ ___ 
 *| |\/| | |   | '__/ _` | |_| __/ _ \ '__/ __|
 *| |  | | |___| | | (_| |  _| ||  __/ |  \__ \
 *|_|  |_|\____|_|  \__,_|_|  \__\___|_|  |___/
 *
*/

use MCrafters\HelpModifer\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;

class ModifedHelpCommand extends Command{
    /** 
     *@var Main 
     */

    private $plugin;
    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        parent::__construct("help", "Gives you help", null, ["?"]);
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     */
    public function sendPageOne(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page1") as $msg){
            $sender->sendMessage($this->replaceStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendConsolePageOne(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page1") as $msg){
            $sender->sendMessage($this->replaceConsoleStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendPageTwo(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page2") as $msg){
            $sender->sendMessage($this->replaceStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendConsolePageTwo(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page2") as $msg){
            $sender->sendMessage($this->replaceConsoleStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendPageThree(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page3") as $msg){
            $sender->sendMessage($this->replaceStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendConsolePageThree(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page3") as $msg){
            $sender->sendMessage($this->replaceConsoleStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendPageFour(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page4") as $msg){
            $sender->sendMessage($this->replaceStrings($msg, $sender));
        }
    }
    
     /**
     * @param CommandSender $sender
     */
    public function sendConsolePageFour(CommandSender $sender){
        foreach($this->plugin->getConfig()->get("page4") as $msg){
            $sender->sendMessage($this->replaceConsoleStrings($msg, $sender));
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
        $msg = str_replace("{playerlevel}", $sender->getLevel()->getName(), $msg);
        $msg = str_replace("{playerlevelcount}", count($sender->getLevel()->getPlayers()), $msg);
        return $msg;
    }
    
     /**
     * @param string $msg
     * @param CommandSender $sender
     * @return string
     */
    public function replaceConsoleStrings($msg, CommandSender $sender){
        $msg = str_replace("&", "§", $msg);
        $msg = str_replace("{name}", "CONSOLE", $msg);
        $msg = str_replace("{maxplayers}", count($this->plugin->getServer()->getMaxPlayers()), $msg);
        $msg = str_replace("{playercount}", count($this->plugin->getServer()->getOnlinePlayers()), $msg);
        $msg = str_replace("{playerlevel}", $this->plugin->getServer()->getDefaultLevel()->getName(), $msg);
        $msg = str_replace("{playerlevelcount}", count($this->plugin->getServer()->getDefaultLevel()->getPlayers()), $msg);
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
        if(count($args) === 0 or trim(implode(" ", $args)) === "1"){
          $this->sendConsolePageOne($sender);
          return true;
          }elseif(trim(implode(" ", $args)) === "2"){
            $this->sendConsolePageTwo($sender);
            return true;
            }elseif(trim(implode(" ", $args)) === "3"){
              $this->sendConsolePageThree($sender);
              return true;
              }elseif(trim(implode(" ", $args)) === "4"){
                $this->sendConsolePageFour($sender);
                return true;
                }
      }elseif($sender instanceof Player){
        if(count($args) === 0 or trim(implode(" ", $args)) === "1"){
          $this->sendPageOne($sender);
          return true;
          }elseif(trim(implode(" ", $args)) === "2"){
            $this->sendPageTwo($sender);
            return true;
            }elseif(trim(implode(" ", $args)) === "3"){
              $this->sendPageThree($sender);
              return true;
              }elseif(trim(implode(" ", $args)) === "4"){
                $this->sendPageFour($sender);
                return true;
                }
          }    
    }
}
