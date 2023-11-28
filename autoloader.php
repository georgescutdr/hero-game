<?php

spl_autoload_register(function () {
    require 'lib/character.php';
    require 'lib/unit_factory.php';
    require 'lib/scene.php';
});