<?php

namespace rakisepunish\command;

use rakisepunish\Manager;
use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class UnmuteCommand extends Command {
    
    public function __construct() {
        parent::__construct("desmutar");
        $this->description = "Permite o jogador falar novamente.";
        $this->usageMessage  = "/desmutar <player>";
        $this->setPermission("rakisepunish.command.unmute");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $muteList = Manager::getNameMutes();
            if (!$muteList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playernMutado"));
                return false;
            }
            $muteList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::GREEN . " foi mutado. ");
        } else {
            $sender->sendMessage(Translation::translate("SemPerm"));
        }
        return true;
    }
}