<?php

class Scene {
	protected $player_one = null;
	protected $player_two = null;
	protected $winner = false;
	protected $turns = 20;

	protected $stats_1 = null;

	protected $stats_2 = null;


	public function __construct() {
		//create some default stats
		$this->stats_1 = array(
					'name' => 'Orderus',
					'health' => rand(70, 100),
					'strength' => rand(70, 80),
					'defence' => rand(45, 55),
					'speed' => rand(40, 50),
					'luck_start' => 10,
					'luck_end' => 30,
					'has_skills' => true
				);

		$this->stats_2 = array(
					'name' => 'Boss',
					'health' => rand(60, 90),
					'strength' => rand(60, 90),
					'defence' => rand(40, 60),
					'speed' => rand(40, 60),
					'luck_start' => 25,
					'luck_end' => 40,
					'has_skills' => false
				);

		//create a new factory
		$unitFactory = new unitFactory();

		//spawn creatures
		$unit_1 = $unitFactory->create($this->stats_1); 
		$unit_2 = $unitFactory->create($this->stats_2);

		//set players to fight in the correct order
		$this->player_one = ($unit_1->getSpeed() > $unit_2->getSpeed() || ($unit_1->getLuck() > $unit_2->getLuck())) ? $unit_1 : $unit_2;
		$this->player_two = $this->player_one === $unit_1 ? $unit_2 : $unit_1;

		$this->showStats(); //show each player's status
	}

	//show each player's status
	private function showStats() {
		echo '<b>Player one</b><br/>';
		$this->player_one->showStats();
		echo '<br/><br/><b>Player two</b><br/>';
		$this->player_two->showStats();
	}

	//check if any character died
	private function setWinner($force = false) {
		
		if($force) {
			//game ended, nobody died, the player with the highest health wins
			$this->winner =  $this->player_one->getHealth() > $this->player_two->getHealth() ? $this->player_one : $this->player_two;
		} else {
			//the player with no health loses
			$this->winner =  $this->player_one->getHealth() <= 0 ? $this->player_two : ($this->player_two->getHealth() <= 0 ? $this->player_one : false);
		}

		if($this->winner) {
			echo "<br><b>Winner:</b> " . $this->winner->getName();
			return true;
		}

		return false;
	}

	//the battle 
	public function startFight() {
		do {
			$this->player_one->strike($this->player_two); // sthe strike is done by the caller upon the one in the parameter
			
			if($this->setWinner()) { //check if somebody died
				break;
			}
			

			$this->player_two->strike($this->player_one);
			
			if($this->setWinner()) {
				break;
			}


			$this->turns--;

		} while ($this->turns > 0);

		//time is up, nobody died
		if(!$this->winner) {
			$this->setWinner(true); //whoever has the highest health wins
		}
	}
}











