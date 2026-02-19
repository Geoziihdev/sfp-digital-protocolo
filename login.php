<?php
session_start();
$arquivoUsuarios = 'usuarios.json';
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioDigitado = trim($_POST['usuario'] ?? '');
    $senhaDigitada   = $_POST['senha'] ?? '';

    if (file_exists($arquivoUsuarios)) {
        $conteudo = file_get_contents($arquivoUsuarios);
        $usuarios = json_decode($conteudo, true); 
        
        $autenticado = false;

        if (is_array($usuarios)) {
            foreach ($usuarios as $u) {
                if (isset($u['usuario']) && isset($u['senha'])) {
                    if ($u['usuario'] === $usuarioDigitado && $u['senha'] === $senhaDigitada) {
                        $autenticado = true;
                        break;
                    }
                }
            }
        }

        if ($autenticado) {
            $_SESSION['usuario'] = $usuarioDigitado;
            header("Location: index.php");
            exit;
        } else {
            $erro = "Usuário ou senha inválidos. Verifique os dados.";
        }
    } else {
        $erro = "Erro interno: Base de dados não encontrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Protocolo Eletrônico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1A5FB4; 
            background-image: linear-gradient(rgba(26,95,180,0.9), rgba(26,95,180,0.9)), url('./img/bg-login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        input:focus {
            outline: 3px solid #FCD34D !important; 
            outline-offset: 2px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <main class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8" role="main">

        <header class="text-center mb-6">
            <img src="img/logo-governo.svg" alt="Brasão do Governo Municipal" class="w-56 mx-auto mb-4">
            <h1 class="text-gray-800 font-bold text-xl mb-1">
                Sistema de Protocolo Eletrônico
            </h1>
            <p class="text-sm text-gray-600">Acesso restrito</p>
        </header>

        <?php if($erro): ?>
            <div role="alert" aria-live="assertive" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-sm font-medium">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo $erro; ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">

            <div class="flex flex-col">
                <label for="usuario" class="text-sm text-gray-700 font-bold mb-2">
                    Usuário ou CPF
                </label>
                <input type="text" id="usuario" name="usuario" required
                    autocomplete="username"
                    class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-blue-600 transition-colors"
                    placeholder="Digite seu usuário">
            </div>

            <div class="flex flex-col">
                <label for="senha" class="text-sm text-gray-700 font-bold mb-2">
                    Senha
                </label>
                <input type="password" id="senha" name="senha" required
                    autocomplete="current-password"
                    class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-blue-600 transition-colors"
                    placeholder="Digite sua senha">
            </div>

            <button type="submit"
                class="w-full bg-[#1A5FB4] hover:bg-[#154a8c] text-white font-bold py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] focus:ring-4 focus:ring-blue-200">
                Entrar no Sistema
            </button>

        </form>

        <nav class="text-center mt-6" aria-label="Links úteis">
            <a href="cadastro.php" class="text-sm text-blue-700 hover:underline font-bold">
                Não tem acesso? Solicite uma conta
            </a>
        </nav>

        <footer class="text-center text-[10px] uppercase tracking-wider text-gray-400 mt-8 border-t pt-4">
            © <?php echo date('Y'); ?> — Secretaria de Finanças Públicas<br>
            <strong>Governo Municipal</strong>
        </footer>

    </main>

</body>
</html>