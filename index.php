<?php
require_once __DIR__ . '/src/biblioteca.php';
require_once __DIR__ . '/src/helpers.php';
require_once __DIR__ . '/src/dados.php';

include 'header.php'; 

$dataAtual = '2025-10-25';
$perfisInput = obterPerfisExemplo();
$emprestimosInput = obterEmprestimosExemplo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataAtual = $_POST['dataAtual'] ?? '2025-10-25';
    $perfisInput = json_decode($_POST['perfis'], true) ?? [];
    $emprestimosInput = json_decode($_POST['emprestimos'], true) ?? [];

    $registrosProcessados = processarEmprestimos($emprestimosInput, $perfisInput, $dataAtual);
    $itensEmAtraso = filtrarItensEmAtraso($registrosProcessados);
    $multasPorUsuario = calcularTotalMultasPorUsuario($registrosProcessados);
    $multasPorMes = calcularTotalMultasPorMes($registrosProcessados);

    $_SESSION['relatorios'] = [
        'registrosProcessados' => $registrosProcessados,
        'itensEmAtraso' => $itensEmAtraso,
        'multasPorUsuario' => $multasPorUsuario,
        'multasPorMes' => $multasPorMes
    ];

    header('Location: relatorios.php');
    exit;
}
?>

<h1>Processar Registros (Entrada)</h1>
<p>Insira os dados em formato JSON para processamento. Os dados de exemplo são carregados automaticamente.</p>

<form action="index.php" method="POST">
    <label for="dataAtual">Data Atual (para cálculo)</label>
    <input type="date" id="dataAtual" name="dataAtual" value="<?= htmlspecialchars($dataAtual) ?>">
    
    <div class="grid">
        <div>
            <label for="perfis">Perfis de Usuários (JSON)</label>
            <textarea id="perfis" name="perfis"><?= htmlspecialchars(json_encode($perfisInput, JSON_PRETTY_PRINT)) ?></textarea>
        </div>
        <div>
            <label for="emprestimos">Registros de Empréstimos (JSON)</label>
            <textarea id="emprestimos" name="emprestimos"><?= htmlspecialchars(json_encode($emprestimosInput, JSON_PRETTY_PRINT)) ?></textarea>
        </div>
    </div>
    <button type="submit">Processar e Ver Relatórios</button>
</form>

<?php
include 'footer.php'; 
?>