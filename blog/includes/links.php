<?php
require_once '../core/Conexao.php';
include 'functions.php';
require_once 'models/Post.php';
require_once 'models/Categoria.php';


$categoriasMenu = Categoria::listarTodas(); // Carrega as categorias ANTES do header
 // Agora o menu funciona corretamente!


require_once 'models/Estatisticas.php';
require_once 'models/Comentario.php';





