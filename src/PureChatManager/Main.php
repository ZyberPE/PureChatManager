<?php

declare(strict_types=1);

namespace PureChatManager;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase {

    private Config $config;

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

        if($command->getName() === "setsuffix") {

            if(!$sender->hasPermission("purechat.use")){
                $sender->sendMessage($this->config->getNested("messages.no-permission"));
                return true;
            }

            if(count($args) < 2){
                $sender->sendMessage("§cUsage: /setsuffix <player> <suffix>");
                return true;
            }

            $target = Server::getInstance()->getPlayerExact($args[0]);

            if(!$target instanceof Player){
                $sender->sendMessage($this->config->getNested("messages.player-not-found"));
                return true;
            }

            $suffix = implode(" ", array_slice($args, 1));

            $pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");

            if($pureChat !== null){
                $pureChat->setSuffix($target, $suffix);
            }

            $sender->sendMessage(
                str_replace(
                    ["{player}", "{suffix}"],
                    [$target->getName(), $suffix],
                    $this->config->getNested("messages.suffix-set-success")
                )
            );

            if($sender !== $target){
                $target->sendMessage(
                    str_replace(
                        ["{type}", "{value}", "{sender}"],
                        ["suffix", $suffix, $sender->getName()],
                        $this->config->getNested("messages.changed-notify")
                    )
                );
            }

            return true;
        }

        if($command->getName() === "setprefix") {

            if(!$sender->hasPermission("purechat.usee")){
                $sender->sendMessage($this->config->getNested("messages.no-permission"));
                return true;
            }

            if(count($args) < 2){
                $sender->sendMessage("§cUsage: /setprefix <player> <prefix>");
                return true;
            }

            $target = Server::getInstance()->getPlayerExact($args[0]);

            if(!$target instanceof Player){
                $sender->sendMessage($this->config->getNested("messages.player-not-found"));
                return true;
            }

            $prefix = implode(" ", array_slice($args, 1));

            $pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");

            if($pureChat !== null){
                $pureChat->setPrefix($target, $prefix);
            }

            $sender->sendMessage(
                str_replace(
                    ["{player}", "{prefix}"],
                    [$target->getName(), $prefix],
                    $this->config->getNested("messages.prefix-set-success")
                )
            );

            if($sender !== $target){
                $target->sendMessage(
                    str_replace(
                        ["{type}", "{value}", "{sender}"],
                        ["prefix", $prefix, $sender->getName()],
                        $this->config->getNested("messages.changed-notify")
                    )
                );
            }

            return true;
        }

        return false;
    }
}
