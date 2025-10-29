<?php
session_start();

$currentFile = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalho - Biblioteca PF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="site-header">
        <div class="header-container">
            <span class="site-title">Sistema de Biblioteca</span>
            <nav class="menu">
                <a href="index.php" class="<?= ($currentFile == 'index.php') ? 'active' : '' ?>">
                    Processar Registros
                </a>
                <a href="cadastro.php" class="<?= ($currentFile == 'cadastro.php') ? 'active' : '' ?>">
                    Gerar Registro (JSON)
                </a>
                <a href="relatorios.php" class="<?= ($currentFile == 'relatorios.php') ? 'active' : '' ?>">
                    Relatórios
                </a>
            </nav>
        </div>
    </header>

    <div class="container">