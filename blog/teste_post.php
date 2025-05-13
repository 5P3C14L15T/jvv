<?php
$data = file_get_contents("php://input");
file_put_contents(__DIR__ . '/teste-log.txt', "✔ Chegou no teste: " . $data . "\n", FILE_APPEND);
