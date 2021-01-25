<?php

namespace bansystem;

use bansystem\command\BanCommand;
use bansystem\command\BanIPCommand;
use bansystem\command\BanListCommand;
use bansystem\command\MuteCommand;
use bansystem\command\PardonCommand;
use bansystem\command\PardonIPCommand;
use bansystem\command\UnmuteCommand;
use bansystem\listener\PlayerChatListener;
use bansystem\listener\PlayerCommandPreproccessListener;
use bansystem\listener\PlayerPreLoginListener;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

class BanSystem extends PluginBase {
    
    private function removeCommand(string $command) {
        $commandMap = $this->getServer()->getCommandMap();
        $cmd = $commandMap->getCommand($command);
        if ($cmd == null) {
            return;
        }
        $cmd->setLabel("");
        $cmd->unregister($commandMap);
    }
    
    private function initializeCommands() {
        $commands = array("banir", "punicoes", "desbanir", "desbanirip", "banirip");
        for ($i = 0; $i < count($commands); $i++) {
            $this->removeCommand($commands[$i]);
        }
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll("rakisepunish", array(
            new BanCommand(),
            new BanIPCommand(),
            new BanListCommand(),
            new MuteCommand(),
            new PardonCommand(),
            new PardonIPCommand(),
            new UnbanCommand(),
            new UnbanIPCommand(),
            new UnmuteCommand()
        ));
    }
    
    /**
     * @param Permission[] $permissions
     */
    protected function addPermissions(array $permissions) {
        foreach ($permissions as $permission) {
            $this->getServer()->getPluginManager()->addPermission($permission);
        }
    }
    
    /**
     * 
     * @param Plugin $plugin
     * @param Listener[] $listeners
     */
    protected function registerListeners(Plugin $plugin, array $listeners) {
        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $plugin);
        }
    }
    
    private function initializeListeners() {
        $this->registerListeners($this, array(
            new PlayerChatListener(),
            new PlayerCommandPreproccessListener(),
            new PlayerPreLoginListener()
        ));
    }
    
    private function initializeFiles() {
        @mkdir($this->getDataFolder());
        if (!(file_exists("muted-players.txt") && is_file("muted-players.txt"))) {
            @fopen("muted-players.txt", "w+");
        }
        }
        }
    }
    
    private function initializePermissions() {
        $this->addPermissions(array(
            new Permission("rakisepunish.command.ban", "Allows the player to prevent the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.banip", "Allows the player to prevent the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.punicoes", "Allows the player to view the players/IP addresses banned on this server.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.mutar", "Allows the player to prevent the given player from sending public chat message.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.desbanir", "Allows the player to allow the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.desbanirip", "Allows the player to allow the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("rakisepunish.command.unmute", "Allows the player to allow the given player to send public chat message.", Permission::DEFAULT_OP)
        ));
    }
    
    private function removeBanExpired() {
        $this->getServer()->getNameBans()->removeExpired();
        $this->getServer()->getIPBans()->removeExpired();
        Manager::getNameMutes()->removeExpired();
        Manager::getIPMutes()->removeExpired();
        Manager::getNameBlocks()->removeExpired();
        Manager::getIPBlocks()->removeExpired();
    }
    
    public function onLoad() {
        $this->getLogger()->info("rakisepunish está carregando...");
    }
    
    public function onEnable() {
        $this->getLogger()->info("rakisepunish está ligado.");
        $this->initializeCommands();
        $this->initializeListeners();
        $this->initializePermissions();
        $this->initializeFiles();
        $this->removeBanExpired();
    }
    
    public function onDisable() {
        $this->getLogger()->info("rakisepunish está desligado.");
    }
}