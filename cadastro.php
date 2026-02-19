<?php
session_start();

$arquivoUsuarios = 'usuarios.json';
$erro = null;
$sucesso = null;

function buscarUsuarios($caminho) {
    if (!file_exists($caminho)) {
        return [];
    }
    $dados = json_decode(file_get_contents($caminho), true);
    return is_array($dados) ? $dados : [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioNovo = trim($_POST['usuario'] ?? '');
    $senhaNova   = $_POST['senha'] ?? '';

    $usuariosAtuais = buscarUsuarios($arquivoUsuarios);

    $jaExiste = false;
    foreach ($usuariosAtuais as $u) {
        if ($u['usuario'] === $usuarioNovo) {
            $jaExiste = true;
            break;
        }
    }

    if (strlen($senhaNova) < 6) {
        $erro = "A senha é muito curta. Use pelo menos 6 caracteres.";
    } elseif (empty($usuarioNovo)) {
        $erro = "O campo usuário é obrigatório.";
    } elseif ($jaExiste) {
        $erro = "Este nome de usuário já está em uso.";
    } else {
        $usuariosAtuais[] = [
            'usuario' => $usuarioNovo,
            'senha'   => $senhaNova 
        ];
        
        file_put_contents($arquivoUsuarios, json_encode($usuariosAtuais, JSON_PRETTY_PRINT));
        $sucesso = "Cadastro realizado com sucesso! Você já pode entrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Sistema de Protocolos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1A5FB4; 
            background-image: linear-gradient(rgba(26,95,180,0.85), rgba(26,95,180,0.85)), 
                            url('./img/bg-login.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        input:focus {
            outline: 3px solid #FCD34D !important;
            outline-offset: 2px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <main class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8 transform transition-all" role="main">
        
        <div class="text-center mb-6">
            <img src="img/logo-governo.svg" alt="Brasão do Governo Municipal" class="w-56 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Criar Acesso</h1>
            <p class="text-sm text-gray-500 font-medium">Cadastre suas credenciais de servidor</p>
        </div>

        <?php if($erro): ?>
            <div role="alert" aria-live="assertive" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-sm">
                <strong>Erro:</strong> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <?php if($sucesso): ?>
            <div role="alert" aria-live="assertive" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 text-sm">
                <strong>Sucesso!</strong> <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">

            <div class="flex flex-col">
                <label for="usuario" class="text-sm font-bold text-gray-700 mb-1">Usuário de Acesso</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Ex: joao.servidor" class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-blue-600 transition-all outline-none">
            </div>

            <div class="flex flex-col">
                <label for="senha" class="text-sm font-bold text-gray-700 mb-1">Senha de Segurança</label>
                <input type="password" id="senha" name="senha" required minlength="6" placeholder="Mínimo 6 caracteres" class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-blue-600 transition-all outline-none">
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-wider">A senha será criptografada</p>
            </div>

            <button type="submit" class="w-full bg-[#1A5FB4] hover:bg-[#154a8c] text-white py-4 rounded-xl font-bold shadow-lg transition-all active:scale-95 focus:ring-4 focus:ring-blue-100">
                Criar Minha Conta
            </button>

        </form>
        
        <nav class="text-center mt-8 border-t pt-6" aria-label="Voltar para login">
            <a href="login.php" class="text-sm text-blue-700 font-bold hover:text-blue-900 transition-colors">
                Já possui acesso? Faça login aqui
            </a>
        </nav>

    </main>

</body>
</html>