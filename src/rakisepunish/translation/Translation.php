<?php

namespace rakisepunish\translation;

use rakisepunish\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "SemPerm":
                return TextFormat::RED . "Você tem que ser do grupo Gerente ou superior para executar esse comando.";
            case "playerOff":
                return TextFormat::GOLD . "Player não está online.";
            case "playerBanido":
                return TextFormat::GOLD . "Esse jogador já foi banido.";
            case "ipBanido":
                return TextFormat::GOLD . "Esse ip já foi banido.";
            case "ipnBanido":
                return TextFormat::GOLD . "Esse ip não está banido.";
            case "playernBanido":
                return TextFormat::GOLD . "Player não está banido.";
            case "playerMutado":
                return TextFormat::GOLD . "Player já está mutado.";
            case "playernMutado":
                return TextFormat::GOLD . "Player is not muted.";
            default:
                throw new TranslationFailedException("Failed to translate.");
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter is empty.");
        }
        switch ($translation) {
            case "funcionamento":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::DARK_GREEN . "funcionamento: " . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parametro do index 0 tem que ser um tipo de comando.");
                }
        }
    }
}