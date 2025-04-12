<?php
$senha = '123'; // sua senha
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo $hash;
