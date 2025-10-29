<?php

const REGRAS_NEGOCIO = [
    'aluno' => [
        'prazo_dias' => 10,
        'isencao_dias_atraso' => 0,
        'multa_por_dia' => 0.75,
    ],
    'professor' => [
        'prazo_dias' => 30,
        'isencao_dias_atraso' => 5,
        'multa_por_dia' => 0.50,
    ],
];

function validarRegistro(array $registro): bool
{
    if (!isset($registro['userId'], $registro['itemId'], $registro['checkoutDate'])) {
        return false;
    }
    if (empty($registro['userId']) || !is_string($registro['userId'])) {
        return false;
    }
    if (DateTime::createFromFormat('Y-m-d', $registro['checkoutDate']) === false) {
        return false;
    }
    if (isset($registro['returnDate']) && $registro['returnDate'] !== null) {
        if (DateTime::createFromFormat('Y-m-d', $registro['returnDate']) === false) {
            return false;
        }
    }
    return true;
}

function ehNumericoEPositivo($valor): bool
{
    return is_numeric($valor) && $valor >= 0;
}

function calcularDataDevolucao(string $dataRetirada, string $perfil): string
{
    $regras = REGRAS_NEGOCIO[$perfil] ?? REGRAS_NEGOCIO['aluno'];
    $prazo = $regras['prazo_dias'];

    $date = new DateTime($dataRetirada);
    $date->modify("+{$prazo} days");
    return $date->format('Y-m-d');
}

function calcularDiasAtraso(string $dataDevolucaoEsperada, ?string $dataDevolucaoReal, string $dataAtual): int
{
    $dataReferencia = $dataDevolucaoReal ?? $dataAtual;
    $esperada = new DateTime($dataDevolucaoEsperada);
    $real = new DateTime($dataReferencia);

    if ($real <= $esperada) {
        return 0;
    }

    $intervalo = $esperada->diff($real);
    return (int)$intervalo->format('%a');
}

function calcularMulta(int $diasAtraso, string $perfil): float
{
    if ($diasAtraso <= 0) {
        return 0.0;
    }

    $regras = REGRAS_NEGOCIO[$perfil] ?? REGRAS_NEGOCIO['aluno'];

    $diasCobraveis = max(0, $diasAtraso - $regras['isencao_dias_atraso']);

    $multa = $diasCobraveis * $regras['multa_por_dia'];

    return max(0.0, $multa);
}

function processarEmprestimos(array $emprestimos, array $perfis, string $dataAtual): array
{
    $emprestimosValidos = array_filter($emprestimos, 'validarRegistro');

    $callbackMap = function ($emprestimo) use ($perfis, $dataAtual) {
        $perfil = $perfis[$emprestimo['userId']] ?? 'aluno';

        $dataDevolucaoEsperada = calcularDataDevolucao($emprestimo['checkoutDate'], $perfil);

        $diasAtraso = calcularDiasAtraso(
            $dataDevolucaoEsperada,
            $emprestimo['returnDate'],
            $dataAtual
        );

        $multa = calcularMulta($diasAtraso, $perfil);

        return [
            'userId' => $emprestimo['userId'],
            'perfil' => $perfil,
            'itemId' => $emprestimo['itemId'],
            'checkoutDate' => $emprestimo['checkoutDate'],
            'returnDate' => $emprestimo['returnDate'],
            'dueDate' => $dataDevolucaoEsperada,
            'lateDays' => $diasAtraso,
            'fine' => $multa,
            'isCurrentlyOverdue' => ($diasAtraso > 0 && $emprestimo['returnDate'] === null),
        ];
    };

    return array_map($callbackMap, $emprestimosValidos);
}

function filtrarItensEmAtraso(array $emprestimosProcessados): array
{
    return array_filter(
        $emprestimosProcessados,
        fn($item) => $item['isCurrentlyOverdue'] === true
    );
}

function calcularTotalMultasPorUsuario(array $emprestimosProcessados): array
{
    $callback = function (array $acumulador, array $item) {
        $userId = $item['userId'];
        $multa = $item['fine'];

        if (!isset($acumulador[$userId])) {
            $acumulador[$userId] = 0.0;
        }

        $acumulador[$userId] += $multa;

        return $acumulador;
    };

    return array_reduce($emprestimosProcessados, $callback, []);
}

function calcularTotalMultasPorMes(array $emprestimosProcessados): array
{
    $callback = function (array $acumulador, array $item) {
        if ($item['fine'] > 0 && $item['returnDate'] !== null) {
            $mesAno = date('Y-m', strtotime($item['returnDate']));

            if (!isset($acumulador[$mesAno])) {
                $acumulador[$mesAno] = 0.0;
            }
            $acumulador[$mesAno] += $item['fine'];
        }
        return $acumulador;
    };

    $resultado = array_reduce($emprestimosProcessados, $callback, []);
    ksort($resultado);
    return $resultado;
}