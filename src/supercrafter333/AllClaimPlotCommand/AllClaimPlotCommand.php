<?php

namespace supercrafter333\AllClaimPlotCommand;

use MyPlot\MyPlot;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class AllClaimPlotCommand extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml", 2);
        if ($config->exists("version") && $config->get("version") == "1.0.0") {
            return;
        } else {
            $this->getLogger()->error("OUTDATED CONFIG.YML!! Your config.yml is outdated! Please delete the file and restart your server to Update the config.yml!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool
    {
        $config = new Config($this->getDataFolder() . "config.yml", 2);
        $myplot = MyPlot::getInstance();
        if ($cmd->getName() == "allclaimplot") {
            if ($s->hasPermission("allclaimplotcommand.allclaimplot.cmd")) {
                foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
                    $freePlot = $myplot->getNextFreePlot("Plots");
                    $myplot->teleportPlayerToPlot($onlinePlayer, $freePlot);
                    $myplot->claimPlot($freePlot, $onlinePlayer->getName());
                }
                $s->sendMessage($config->get("successfull-message"));
                $this->getServer()->broadcastMessage($config->get("broadcast-successfull-message"));
            } else {
                $s->sendMessage($config->get("no-permission-message"));
            }
        }
        return true;
    }
}