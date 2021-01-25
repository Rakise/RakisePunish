<?php

namespace rakisepunish\command;

use rakisepunish\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class PardonIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("desbanirip");
        $this->description = "desbane o ip de um jogador.";
        $this->usageMessage = "/desbanirip <address>";
        $this->setPermission("rakisepunish.command.desbanirip");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("funcionamento", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getIPBans();
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipnBanido"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::GREEN . "IP " . TextFormat::AQUA . $args[0] . TextFormat::GREEN . " Desbanido.");
        } else {
            $sender->sendMessage(Translation::translate("SemPerm"));
        }
        return true;
    }
}