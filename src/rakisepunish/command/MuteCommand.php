<?php

namespace rakisepunish\command;

use rakisepunish\Manager;
use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MuteCommand extends Command {
    
    public function __construct() {
        parent::__construct("mutar");
        $this->description = "Muta um jogador e ele fica sem falar no chat.";
        $this->usageMessage = "/mutar <player> [reason...]";
        $this->setPermission("rakisepunish.command.mutar");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $muteList = Manager::getNameMutes();
            if ($muteList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerMutado"));
                return false;
            }
            if (count($args) == 1) {
                if ($player != null) {
                    $muteList->addBan($player->getName(), null, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " Foi mutado.");
                    $player->sendMessage(TextFormat::RED . "Você foi mutado.");
                } else {
                    $muteList->addBan($args[0], null, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " Foi mutado.");
                }
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($player != null) {
                    $muteList->addBan($player->getName(), $reason, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " foi mutado por " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                    $player->sendMessage(TextFormat::RED . "Você foi mutado por " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                } else {
                    $muteList->addBan($args[0], $reason, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " foi mutado por " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                }
            }
        } else {
            $sender->sendMessage(Translation::translate("SemPerm"));
        }
        return true;
    }
}