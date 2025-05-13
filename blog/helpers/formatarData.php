<?php
function formatarDataExtenso($data)
{
    $meses = [
        '01' => 'janeiro',
        '02' => 'fevereiro',
        '03' => 'março',
        '04' => 'abril',
        '05' => 'maio',
        '06' => 'junho',
        '07' => 'julho',
        '08' => 'agosto',
        '09' => 'setembro',
        '10' => 'outubro',
        '11' => 'novembro',
        '12' => 'dezembro'
    ];

    $timestamp = strtotime($data);
    $dia = date('d', $timestamp);
    $mes = date('m', $timestamp);
    $ano = date('Y', $timestamp);
    $hora = date('H:i', $timestamp);

    return "{$dia} de {$meses[$mes]} de {$ano} às {$hora}";
}
