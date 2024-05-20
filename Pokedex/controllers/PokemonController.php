<?php
class PokemonController
{
    private $model;
    private $tipoPokemonModel;
    private $pokemons;

    public function __construct($model, $tipoPokemonModel)
    {
        $this->model = $model;
        $this->tipoPokemonModel = $tipoPokemonModel;
    }

    public function list()
    {
        if ($this->pokemons === null) {
            $this->pokemons = $this->model->getAllPokemons();
        }
        $result = $this->pokemons;
        $pokemonListPath = $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/views/template/pokemonList.php';
        include_once($pokemonListPath);
    }

    public function filter()
    {
        $tipo = $_POST['tipo'];
        $nombre = $_POST['nombre'];
        $this->pokemons = $this->model->getFilteredPokemons($tipo, $nombre);
        $result = $this->pokemons;
        $pokemonListPath = $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/views/template/pokemonList.php';
        include_once($pokemonListPath);
    }
}