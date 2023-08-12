<?php

namespace Laith98Dev\LevelSystem;

/*  
 *  A plugin for PocketMine-MP.
 *  
 *	 _           _ _   _    ___   ___  _____             
 *	| |         (_) | | |  / _ \ / _ \|  __ \            
 *	| |     __ _ _| |_| |_| (_) | (_) | |  | | _____   __
 *	| |    / _` | | __| '_ \__, |> _ <| |  | |/ _ \ \ / /
 *	| |___| (_| | | |_| | | |/ /| (_) | |__| |  __/\ V / 
 *	|______\__,_|_|\__|_| |_/_/  \___/|_____/ \___| \_/  
 *	
 *	Copyright (C) 2021 Laith98Dev
 *  
 *	Youtube: Laith Youtuber
 *	Discord: Laith98Dev#0695
 *	Github: Laith98Dev
 *	Email: help@laithdev.tk
 *	Donate: https://paypal.me/Laith113
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 	
 */

use pocketmine\player\IPlayer;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\player\chat\LegacyRawChatFormatter;

use _64FF00\PurePerms\EventManager\PPRankChangedEvent;

class EventListener implements Listener 
{
	public function __construct(
		private Main $plugin
		){
		// NOOP
	}
	
	public function getPlugin(){
		return $this->plugin;
	}
	
	public function getDataFolder(){
		return $this->plugin->getDataFolder();
	}

	/**
	 * @param PPRankChangedEvent $event
	 * @priority HIGHEST
	 */
	public function onRankChanged(PPRankChangedEvent $event)
    {
        /** @var IPlayer $player */
        $player = $event->getPlayer();
		if($player instanceof Player){
            if($this->getPlugin()->pureChat !== null){
				$lvl = LvlColor::getColorLevel($player);
				$WorldName = $this->getPlugin()->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getWorld()->getDisplayName() : null;
				$nametag = $this->getPlugin()->pureChat->getNametag($player, $WorldName);
				$nametag = str_replace("{lvl}", $lvl, $nametag);
				$player->setNameTag($nametag);
			}
		}
    }
	
	/**
	 * @param PlayerJoinEvent $event
	 * @priority HIGHEST
	 */
	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		if($player instanceof Player){
			$this->getPlugin()->getDataManager()->checkAccount($player);

			if($this->getPlugin()->pureChat !== null){
				$lvl = LvlColor::getColorLevel($player);
				$WorldName = $this->getPlugin()->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getWorld()->getDisplayName() : null;
				$nametag = $this->getPlugin()->pureChat->getNametag($player, $WorldName);
				$nametag = str_replace("{lvl}", $lvl, $nametag);
				$player->setNameTag($nametag);
			}
		}
	}
	
	/**
	 * @param BlockPlaceEvent $event
	 * @priority HIGHEST
	 */
	public function onPlace(BlockPlaceEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlockAgainst();
		if($event->isCancelled())
			return;
		
		if($player instanceof Player){
			$cfg = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
			if($cfg->get("plugin-enable") === true){
				if($cfg->get("add-xp-by-build") === true && in_array($block->getTypeId(), $cfg->get("blocks-list", []))){
					if(mt_rand(0, 200) < 120 && mt_rand(0, 1) == 1 && mt_rand(0, 1) == 0 && mt_rand(0, 3) == 2){// random
						if($this->plugin->getDataManager()->addXP($player, $this->plugin->getDataManager()->getAddXpCount($player))){
							$player->sendPopup(TF::YELLOW . "+" . $this->plugin->getDataManager()->getAddXpCount($player) . " XP");
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param BlockBreakEvent $event
	 * @priority HIGHEST
	 */
	public function onBreak(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if($event->isCancelled())
			return;
		
		if($player instanceof Player){
			$cfg = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
			if($cfg->get("plugin-enable") && $cfg->get("plugin-enable") === true){
				if($cfg->get("add-xp-by-destroy") && $cfg->get("add-xp-by-destroy") === true && in_array($block->getTypeId(), $cfg->get("blocks-list", []))){
					if(mt_rand(0, 200) < 120 && mt_rand(0, 1) == 1 && mt_rand(0, 1) == 0 && mt_rand(0, 3) == 2){// random
						if($this->plugin->getDataManager()->addXP($player, $this->plugin->getDataManager()->getAddXpCount($player))){
							$player->sendPopup(TF::YELLOW . "+" . $this->plugin->getDataManager()->getAddXpCount($player) . " XP");
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param PlayerDeathEvent $event
	 */
	public function onDeath(PlayerDeathEvent $event)
	{
		$player = $event->getPlayer();
		if($player instanceof Player){
			$cfg = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
			if($cfg->get("plugin-enable") === true){
				if($cfg->get("add-xp-by-kill") === true){
					if($cfg->get("kill-with-death-screen") === true){
						if(mt_rand(0, 200) < 120 && mt_rand(0, 1) == 1 && mt_rand(0, 1) == 0 && mt_rand(0, 3) == 2){// random
							if($this->getPlugin()->getDataManager()->addXP($player, $this->getPlugin()->getDataManager()->getAddXpCount($player))){
								$player->sendPopup(TF::YELLOW . "+" . $this->getPlugin()->getDataManager()->getAddXpCount($player) . " XP");
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param EntityDamageEvent $event
	 * @priority HIGHEST
	 */
	public function onDamage(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();

		if($event->isCancelled())
			return;
		
		if($entity instanceof Player){
			if($event instanceof EntityDamageByEntityEvent && ($damager = $event->getDamager()) instanceof Player){
				$cfg = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
				if($cfg->get("plugin-enable") === true){
					if($cfg->get("add-xp-by-kill") === true){
						if($cfg->get("kill-with-death-screen") === false){
							if($entity->getHealth() <= $event->getFinalDamage()){
								if(mt_rand(0, 200) < 120 && mt_rand(0, 1) == 1 && mt_rand(0, 1) == 0 && mt_rand(0, 3) == 2){// random
									if($this->getPlugin()->getDataManager()->addXP($damager, $this->getPlugin()->getDataManager()->getAddXpCount($damager))){
										$damager->sendPopup(TF::YELLOW . "+" . $this->getPlugin()->getDataManager()->getAddXpCount($damager) . " XP");
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param PlayerChatEvent $event
	 * @priority HIGHEST
	 */
	public function onChat(PlayerChatEvent $event)
	{
		$player = $event->getPlayer();
		$message = $event->getMessage();

		if($event->isCancelled())
			return;

		if($player instanceof Player){
			$cfg = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
			if($cfg->get("plugin-enable") === true){
				if($cfg->get("add-xp-by-chat") === true){
					if(mt_rand(0, 200) < 120 && mt_rand(0, 1) == 1 && mt_rand(0, 1) == 0 && mt_rand(0, 3) == 2){// random
						if($this->getPlugin()->getDataManager()->addXP($player, $this->getPlugin()->getDataManager()->getAddXpCount($player))){
							$player->sendPopup(TF::YELLOW . "+" . $this->getPlugin()->getDataManager()->getAddXpCount($player) . " XP");
						}
					}
				}
				
				// chat format
				$lvl = LvlColor::getColorLevel($player);
				if($cfg->get("edit-chat-format") === true){
					if($this->getPlugin()->pureChat !== null){
						$WorldName = $this->getPlugin()->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getWorld()->getDisplayName() : null;
						$chatFormat = $this->getPlugin()->pureChat->getChatFormat($player, $message, $WorldName);
						$chatFormat = str_replace("{lvl}", $lvl, $chatFormat);
						$event->setFormatter(new LegacyRawChatFormatter($chatFormat)); 
					} 
					/* else {
						if($cfg->get("chatFormat") && $cfg->get("chatFormat") !== ""){
							$chatFormat = str_replace(["{name}", "{lvl}", "{msg}", "&"], [$player->getName(), $lvl, $message, TF::ESCAPE], $cfg->get("chatFormat"));
							//$event->setFormat($chatFormat);
							$event->cancel();
							$this->getPlugin()->getServer()->broadcastMessage($chatFormat);
						}
					} */
				}
			}
		}
	}
}
