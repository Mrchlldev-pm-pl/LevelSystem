<?php

namespace Laith98Dev\LevelSystem;

use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

class LvlColor {
  
  public static function getLevel(Player $player): float{
      return Main::getInstance()->getDataManager()->getLevel($player);
  }
  
	/**
     * @param Player $player
     * @return string
     */
	public static function getColorLevel(Player $player) :string{
		$result = "";
		$level = self::getLevel($player);
		if($level >= 0){
			$result = TextFormat::GRAY.$level;
	        }
	        if($level >= 5){
			$result = TextFormat::DARK_GRAY.$level;
		}
		if($level >= 10){
			$result = TextFormat::YELLOW.$level;	
		}
		if($level >= 15){
			$result = TextFormat::GOLD.$level;
		}
		if($level >= 20){
			$result = TextFormat::AQUA.$level;
		}
		if($level >= 30){
			$result = TextFormat::LIGHT_PURPLE.$level;
		}
		if($level >= 40){
			$result = TextFormat::DARK_PURPLE.$level;
		}
                if($level >= 50){
                        $result = TextFormat::GREEN.$level;
		}
		if($level >= 65){
			$result = TextFormat::DARK_GREEN.$level;
		}
		if($level >= 80){
			$result = TextFormat::RED.$level;
		}
		if($level >= 100){
			$result = TextFormat::BLUE.$level;	
		}
		if($level >= 125){
			$result = TextFormat::DARK_BLUE.$level;
		}
		if($level >= 150){
			$result = TextFormat::DARK_RED.$level;
		}
		if($level >= 175){
			$result = TextFormat::DARK_AQUA.$level;
		}
		if($level >= 200){
			$result = TextFormat::BLACK.$level;
		}
                if($level >= 225){
                        $result = TextFormat::WHITE.$level;
		}
		if($level >= 250){
			$result = TextFormat::MINECOIN_GOLD.$level;
		}
		if($level >= 275){
			$result = TextFormat::BOLD . TextFormat::GRAY.$level;
	        }
	        if($level >= 300){
			$result = TextFormat::BOLD . TextFormat::DARK_GRAY.$level;
		}
		if($level >= 350){
			$result = TextFormat::BOLD . TextFormat::YELLOW.$level;	
		}
		if($level >= 400){
			$result = TextFormat::BOLD . TextFormat::GOLD.$level;
		}
		if($level >= 450){
			$result = TextFormat::BOLD . TextFormat::AQUA.$level;
		}
		if($level >= 500){
			$result = TextFormat::BOLD . TextFormat::LIGHT_PURPLE.$level;
		}
		if($level >= 575){
			$result = TextFormat::BOLD . TextFormat::DARK_PURPLE.$level;
		}
                if($level >= 650){
                        $result = TextFormat::BOLD . TextFormat::GREEN.$level;
		}
		if($level >= 725){
			$result = TextFormat::BOLD . TextFormat::DARK_GREEN.$level;
		}
		if($level >= 800){
			$result = TextFormat::BOLD . TextFormat::RED.$level;
		}
		if($level >= 900){
			$result = TextFormat::BOLD . TextFormat::BLUE.$level;	
		}
		if($level >= 1000){
			$result = TextFormat::BOLD . TextFormat::DARK_BLUE.$level;
		}
		if($level >= 1200){
			$result = TextFormat::BOLD . TextFormat::DARK_RED.$level;
		}
		if($level >= 1400){
			$result = TextFormat::BOLD . TextFormat::DARK_AQUA.$level;
		}
		if($level >= 1600){
			$result = TextFormat::BOLD . TextFormat::BLACK.$level;
		}
                if($level >= 1800){
                        $result = TextFormat::BOLD . TextFormat::WHITE.$level;
		}
		if($level >= 2000){
			$result = TextFormat::BOLD . TextFormat::MINECOIN_GOLD.$level;
		}
		return $result;
	}
}
