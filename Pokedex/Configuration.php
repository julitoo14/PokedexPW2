<?php
include_once ('./scripts/database.php');

class Configuration
{
    public static function getDatabase(){
        $config = self::getConfig();
        return new Database($config['servername'], $config['user'], $config['pass'], $config['db']);
    }

    public static function getPokemonController(){
        return new PokemonController(self::getPokemonModel(), self::getTipoPokemonModel());
    }

    public static function getPokemonModel()
    {
        return new PokemonModel(self::getDatabase());
    }

    public static function getTipoPokemonModel()
    {
        return new TipoPokemonModel(self::getDatabase());
    }

    public static function getConfig()
    {
        return parse_ini_file('config.ini');
    }
}