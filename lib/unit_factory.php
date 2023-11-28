<?php

class unitFactory {

	public function create($stats) {
		return new Character($stats);
	}
}