<?php
file_put_contents(__DIR__ . '/log.txt', "Funcionando em " . date('H:i:s') . PHP_EOL, FILE_APPEND);
echo "Testado";
