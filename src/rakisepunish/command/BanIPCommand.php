<?php

namespace rakisepunish\command;

use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BanIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("banirip");
        $this->description = "Bane um ip do servidor.";
        $this->usageMessage  = "/banirip <player | address> [reason...]";
        $this->setPermission("rakisepunish.command.banip");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getIPBans();
            if ($banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipBanido"));
                return false;
            }
            $ip = filter_var($args[0], FILTER_VALIDATE_IP);
            $player = $sender->getServer()->getPlayer($args[0]);
            if (count($args) == 1) {
                if ($ip != null) {
                    $banList->addBan($ip, null, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $onlinePlayers) {
                        if ($onlinePlayers->getAddress() == $ip) {
                            $onlinePlayers->kick(TextFormat::RED . "Você foi banido por um staff, caso você pense que foi banido injustamente peça a revisão à algum staff.", false);
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "o ip " . TextFormat::AQUA . $ip . TextFormat::RED . " teve seu ip banido.");
                } else {
                    if ($player != null) {
                        $banList->addBan($player->getAddress(), null, null, $sender->getName());
                        $player->kick(TextFormat::RED . "Você foi banido por um staff, caso você pense que foi banido injustamente peça a revisão à algum staff.", false);
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " teve seu ip banido.");
                    } else {
                        $sender->sendMessage(Translation::translate("playerOff"));
                    }
                }
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($ip != null) {
                    $sender->getServer()->getIPBans()->addBan($ip, $reason, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $players) {
                        if ($players->getAddress() == $ip) {
                            $players->kick(TextFormat::RED . "Seu ip foi banido por: " . TextFormat::AQUA . $reason . TextFormat::RED . ".", false);
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "o IP " . TextFormat::AQUA . $ip . TextFormat::RED . " teve seu ip banido por " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                } else {
                    if ($player != null) {
                        $banList->addBan($player->getAddress(), $reason, null, $sender->getName());
                        $player->kick(TextFormat::RED . "Seu ip foi banido por " . TextFormat::AQUA . $reason . TextFormat::RED . ".", false);
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " teve seu ip banido por: " . TextFormat::AQUA . $reason . TextFormat::RED . ".");  
                    } else {
                        $sender->sendMessage(Translation::translate("playerOff"));
                    }
                }
            } else {
                $sender->sendMessage(Translation::translate("SemPerm"));
            }
        }
        return true;
    }
}