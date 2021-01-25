<?php

namespace rakisepunish\command;

use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BanCommand extends Command {
    
    public function __construct() {
        parent::__construct("banir");
        $this->description = "Bane um jogador do servidor.";
        $this->usageMessage = "/banir <player> [reason...]";
        $this->setPermission("rakisepunish.command.ban");
    }
    
    public function execute(CommandSender $sender, $label, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $banList = $sender->getServer()->getNameBans();
            $playerName = $args[0];
            if ($banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerBanido"));
                return false;
            }
            if (count($args) == 1) {
                if ($player != null) {
                    $banList->addBan($player->getName(), null, null, $sender->getName());
                    $player->kick(TextFormat::RED . "Você foi banido por um staff, caso você pense que foi banido injustamente peça a revisão à algum staff.", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], null, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName . TextFormat::RED . " Foi banido.");
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($player != null) {
                    $banList->addBan($player->getName(), $reason, null, $sender->getName());
                    $player->kick(TextFormat::RED . "Você foi banido por: " . TextFormat::AQUA . $reason . TextFormat::RED . ". caso você foi punido injustamente peça uma revisão com um staff", false);
                    $playerName = $player->getName();
                } else {
                    $banList->addBan($args[0], $reason, null, $sender->getName());
                }
                $sender->getServer()->broadcastMessage(TextFormat::AQUA . $playerName . TextFormat::RED . " FOi banido por "
                        . TextFormat::AQUA . $reason . TextFormat::RED . ".");
            }
        } else {
            $sender->sendMessage(Translation::translate("SemPerm"));
        }
        return true;
    }
}