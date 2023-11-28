<?php

interface Unit {
	public function strike(&$character);
	public function rapidStrike($damage, $name = '');
	public function magicShield($damage, $name = '');
}

class Character implements Unit{
	private $name = '';
	private $health = 0;
	private $strength = 0;
	private $defence = 0;
	private $speed = 0;
	private $luck_start = 0;
	private $luck_end = 0;
	private $luck = 0;
	private $hasSkills = false;

	public function getName() {
		return $this->name;
	}

	public function setName($name = '') {
		$this->name = $name;
	}

	public function getHealth() {
		return $this->health;
	}

	public function getDefence() {
		return $this->defence;
	}

	public function setHealth($health = 0) {
		$this->health = $health;
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function getStrength() {
		return $this->strength;
	}

	public function getLuck() {
		return $this->luck;
	}

	public function getMaxLuck() {
		return $this->luck_end;
	}

	public function setLuck($luck) {
		$this->luck = $luck;
	}

	public function hasSkills($skills = null) {
		if($skills === null) {
			return $this->hasSkills;
		} else {
			$this->hasSkills = $skills;
		}
	}

	public function createStats($stats) {
		$this->name = $stats['name'];
		$this->health = $stats['health'];
		$this->strength = $stats['strength'];
		$this->defence = $stats['defence'];
		$this->speed = $stats['speed'];
		$this->luck_start = $stats['luck_start'];
		$this->luck_end = $stats['luck_end'];
		$this->hasSkills = $stats['has_skills'];
		$this->luck = rand($stats['luck_start'], $stats['luck_end']);
	}

	public function showStats() {
		echo "<table><tr><td><strong>Name: </strong></td><td>";
		echo $this->name;
		echo "</td></tr><tr><td><strong>Health: </strong></td><td>";
		echo $this->health;
		echo "</td></tr><tr><td><strong>Strength: </strong></td><td>";
		echo $this->strength;
		echo "</td></tr><tr><td><strong>Defence: </strong></td><td>";
		echo $this->defence;
		echo "</td></tr><tr><td><strong>Speed: </strong></td><td>";
		echo $this->speed;
		echo "</td></tr><tr><td><strong>Luck: </strong></td><td>";
		echo $this->luck;
		echo "</td></tr></table>";
	}

	public function __construct($stats) {
		$this->createStats($stats);
	}

	//set defender's health according to the atacker's strength
	public function strike(&$character) {

		echo '</br>' . $this->getName() . ' strikes ' . $character->getName() . '</br>';

		//recalculate each player's luck for each round
		$this->setLuck(rand($this->luck_start, $this->luck_end)); //attacker
		$character->setLuck(rand($character->luck_start, $character->luck_end)); //defender

		//strike works only if the defender gets less lucky than it's max luck
		if($character->getLuck() < $character->getMaxLuck()) {
			$damage = $this->getStrength() - $character->getDefence(); //damage = attacker strength - defender defence

			//keep track of the special skills
			if($this->hasSkills()) {
				$damage = $this->rapidStrike($damage, $this->getName()); //do 2 strikes, if the attaker has rapid strike activated
			}
			
			if($character->hasSkills()) {
				$damage = $character->magicShield($damage, $character->getName()); //reduce the damage if the character has magic shield activated
				
			}
			
			//update defender's health
			$character->setHealth($character->getHealth() - $damage);
			$health = $character->getHealth() < 0 ? 0 : $character->getHealth();
			echo '</br>' . $character->getName() . "'s health is now " . $health . '</br>';
		} else {
			//attacker missed the target
			echo $this->getName() . ' missed.' . '</br>';
		}
	}

	//attacker has two strikes
	public function rapidStrike($damage, $name = '') {
		if(rand(0, 100) < 10) {
			echo '</br>' . $name . ' has rapid strike activated. Inflicts double damage.<br/>';
			return $damage * 2;
		} else {
			return $damage;
		}
	}

	//defender takes only 50% damage
	public function magicShield($damage, $name = '') {
		if(rand(0, 100) < 20) {
			echo '</br>' . $name . ' has magic shield activated. Damage reduced by 50%.<br/>';
			return $damage / 2;
		} else {
			return $damage;
		}
	}
}