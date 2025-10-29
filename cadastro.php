<?php
include 'header.php';

$jsonOutput = '';
$registroParaJson = [
    'userId' => '',
    'itemId' => '',
    'checkoutDate' => '',
    'returnDate' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registroParaJson['userId'] = $_POST['userId'] ?? 'u_novo';
    $registroParaJson['itemId'] = $_POST['itemId'] ?? 'item_novo';
    $registroParaJson['checkoutDate'] = $_POST['checkoutDate'] ?? date('Y-m-d');

    if (!empty($_POST['returnDate'])) {
        $registroParaJson['returnDate'] = $_POST['returnDate'];
    } else {
        $registroParaJson['returnDate'] = null;
    }
    
    $jsonOutput = json_encode([$registroParaJson], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
?>

<h1>Gerador de Registro (JSON)</h1>
<p>Preencha o formulário abaixo para gerar o JSON de um novo registro de empréstimo. Copie o resultado e cole na caixa "Registros de Empréstimos" da página <a href="index.php">"Processar Registros"</a>.</p>

<form action="cadastro.php" method="POST">
    <div class="grid">
        <div>
            <label for="userId">User ID</label>
            <input type="text" id="userId" name="userId" required>
        </div>
        <div>
            <label for="itemId">Item ID</label>
            <input type="text" id="itemId" name="itemId" required>
        </div>
        <div>
            <label for="checkoutDate">Data de Retirada</label>
            <input type="date" id="checkoutDate" name="checkoutDate" required>
        </div>
        <div>
            <label for="returnDate">Data de Devolução (Opcional)</label>
            <input type="date" id="returnDate" name="returnDate">
        </div>
    </div>
    <button type="submit">Gerar JSON</button>
</form>

<?php if ($jsonOutput): ?>
    <h2>JSON Gerado:</h2>
    <p>Copie o texto abaixo:</p>
    <textarea style="height: 150px;"><?= htmlspecialchars($jsonOutput) ?></textarea>
<?php endif; ?>

<?php
include 'footer.php';
?>