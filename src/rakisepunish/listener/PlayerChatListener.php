<?php

namespace rakisepunish\listener;

use rakisepunish\Manager;
use rakisepunish\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class PlayerChatListener implements Listener {
    
    public function onPlayerChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $muteList = Manager::getNameMutes();
        if ($muteList->isBanned($player->getName())) {
            $entries = $muteList->getEntries();
            $entry = $entries[strtolower($player->getName())];
            $muteMessage = "";
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                if ($reason != null || $reason != "") {
                    $muteMessage = TextFormat::RED . "você está mutado por " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $muteMessage = TextFormat::RED . "Você está mutado.";
                }
            $event->setCancelled(true);
            $player->sendMessage($muteMessage);
        }
    }
}