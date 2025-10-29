<?php
require_once __DIR__ . '/src/helpers.php';
include 'header.php';

$relatorios = $_SESSION['relatorios'] ?? null;

?>

<h1>Relatórios de Processamento</h1>

<?php if (empty($relatorios)): ?>
    
    <p>Nenhum dado processado. Por favor, vá para a página <a href="index.php">"Processar Registros"</a> e envie os dados primeiro.</p>

<?php else: 
    $itensEmAtraso = $relatorios['itensEmAtraso'];
    $multasPorUsuario = $relatorios['multasPorUsuario'];
    $multasPorMes = $relatorios['multasPorMes'];
    $registrosProcessados = $relatorios['registrosProcessados'];
?>

    <h2>Relatório: Itens Atualmente em Atraso</h2>
    <?php if (empty($itensEmAtraso)): ?>
        <p>Nenhum item em atraso no momento.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Item ID</th>
                    <th>Data Retirada</th>
                    <th>Devolução Esperada</th>
                    <th>Dias Atrasado</th>
                    <th>Multa (calculada)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itensEmAtraso as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['userId']) ?> (<?= htmlspecialchars($item['perfil']) ?>)</td>
                        <td><?= htmlspecialchars($item['itemId']) ?></td>
                        <td><?= htmlspecialchars($item['checkoutDate']) ?></td>
                        <td><?= htmlspecialchars($item['dueDate']) ?></td>
                        <td><?= htmlspecialchars($item['lateDays']) ?></td>
                        <td><?= htmlspecialchars(formatarMoeda($item['fine'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="grid">
        <div>
            <h2>Relatório: Total de Multas por Usuário</h2>
            <table>
                <thead><tr><th>Usuário</th><th>Total Multa</th></tr></thead>
                <tbody>
                    <?php foreach ($multasPorUsuario as $userId => $total): ?>
                        <tr>
                            <td><?= htmlspecialchars($userId) ?></td>
                            <td><?= htmlspecialchars(formatarMoeda($total)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div>
            <h2>Relatório: Total de Multas por Mês (devolvidos)</h2>
            <table>
                <thead><tr><th>Mês (AAAA-MM)</th><th>Total Arrecadado</th></tr></thead>
                <tbody>
                    <?php foreach ($multasPorMes as $mes => $total): ?>
                        <tr>
                            <td><?= htmlspecialchars($mes) ?></td>
                            <td><?= htmlspecialchars(formatarMoeda($total)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <h2>Depuração: Todos os Registros Processados</h2>
    <pre><?= htmlspecialchars(print_r($registrosProcessados, true)) ?></pre>

<?php endif; ?>

<?php
include 'footer.php'; 
?>