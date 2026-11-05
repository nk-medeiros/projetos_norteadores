<?php
session_start();

// Credenciais de demonstração
$usuarioCorreto = "nayara";
$senhaCorreta   = "1234";

// Se alguém abrir o login.php direto (sem enviar o formulário), volta para o início
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$usuario = trim($_POST["usuario"] ?? "");
$senha   = $_POST["senha"] ?? "";

// Salva um registro do acesso no arquivo dados/acessos.csv
// (data e hora, usuário digitado e resultado). A senha NUNCA é salva.
function salvarAcesso($usuario, $resultado) {
    $pasta = __DIR__ . "/dados";
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }
    $arquivo = fopen($pasta . "/acessos.csv", "a");
    if ($arquivo) {
        flock($arquivo, LOCK_EX);
        fputcsv($arquivo, [date("d/m/Y H:i:s"), $usuario, $resultado]);
        flock($arquivo, LOCK_UN);
        fclose($arquivo);
    }
}

// 1) Campos vazios
if ($usuario === "" || $senha === "") {
    header("Location: index.php?erro=1");
    exit;
}

// 2) Usuário e senha corretos
if ($usuario === $usuarioCorreto && hash_equals($senhaCorreta, $senha)) {
    salvarAcesso($usuario, "sucesso");
    session_regenerate_id(true);          // segurança: novo id de sessão
    $_SESSION["usuario"] = $usuario;
    header("Location: painel.php");
    exit;
}

// 3) Dados incorretos
salvarAcesso($usuario, "falha");
header("Location: index.php?erro=2");
exit;
