<?php
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function detectarDispositivo($userAgent) {
    $dispositivo = 'Computador';

    if (preg_match('/mobile/i', $userAgent)) {
        $dispositivo = 'Celular';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        $dispositivo = 'Tablet';
    }

    return $dispositivo;
}

function detectarNavegador($userAgent) {
    if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
    if (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) return 'Opera';
    if (strpos($userAgent, 'Edge') !== false) return 'Edge';
    if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
    if (strpos($userAgent, 'Safari') !== false) return 'Safari';
    if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident/7') !== false) return 'Internet Explorer';

    return 'Desconhecido';
}

function detectarSO($userAgent) {
    if (preg_match('/linux/i', $userAgent)) return 'Linux';
    if (preg_match('/macintosh|mac os x/i', $userAgent)) return 'Mac';
    if (preg_match('/windows|win32/i', $userAgent)) return 'Windows';

    return 'Desconhecido';
}

// Função para formatar a data no formato "15 de abril de 2025"
function formatarDataExtenso($data)
{
    $meses = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];
    $timestamp = strtotime($data);
    $dia = date('d', $timestamp);
    $mes = $meses[(int)date('m', $timestamp)];
    $ano = date('Y', $timestamp);
    return "$dia de $mes de $ano";
}


if (!function_exists('destacarTermo')) {
    function destacarTermo($texto, $termo) {
        if (!$termo) return htmlspecialchars($texto);
        $termoRegex = '/' . preg_quote($termo, '/') . '/i';
        return preg_replace_callback($termoRegex, function($match) {
            return '<span class="text-warning fw-bold">' . htmlspecialchars($match[0]) . '</span>';
        }, htmlspecialchars($texto));
    }
}




