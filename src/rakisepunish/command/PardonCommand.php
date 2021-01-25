<?php

namespace rakisepunish\command;

use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class PardonCommand extends Command {
    
    public function __construct() {
        parent::__construct("desbanir");
        $this->description = "Desbane o player.";
        $this->usageMessage = "/desbanir <player>";
        $this->setPermission("rakisepunish.command.desbanir");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getNameBans();
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playernBanido"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::GREEN . " has been unbanned.");
        } else {
            $sender->sendMessage(Translation::translate("SemPerm"));
        }
        return true;
    }
}