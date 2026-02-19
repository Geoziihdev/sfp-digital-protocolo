<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$arquivoProtocolos = 'protocolos.json';
$usuarioLogado = $_SESSION['usuario'];
$mostrarMensagem = false;

function buscarProtocolos($caminho) {
    if (!file_exists($caminho)) {
        file_put_contents($caminho, '[]'); 
        return [];
    }
    $dados = json_decode(file_get_contents($caminho), true);
    return is_array($dados) ? $dados : [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = trim($_POST['tipo'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    $novoProtocolo = [
        'id' => uniqid(),
        'usuario' => $usuarioLogado, 
        'data' => date('d/m/Y'),
        'status' => 'Recebido',
        'cor' => 'bg-blue-600',
        'tipo' => $tipo,
        'assunto' => $assunto,
        'descricao' => $descricao
    ];

    $todos = buscarProtocolos($arquivoProtocolos);
    array_unshift($todos, $novoProtocolo); 
    file_put_contents($arquivoProtocolos, json_encode($todos, JSON_PRETTY_PRINT));
    
    $mostrarMensagem = true;
}

$todosProtocolos = buscarProtocolos($arquivoProtocolos);
$protocolos = array_filter($todosProtocolos, function($p) use ($usuarioLogado) {
    return $p['usuario'] === $usuarioLogado;
});
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFP 1Doc - Gestão de Protocolos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F1F5F9; }
        .card-shadow { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); }
        *:focus { outline: 3px solid #1A5FB4; outline-offset: 2px; }
    </style>
</head>

<body class="pb-24">

    <nav class="bg-[#1A5FB4] px-4 py-3 flex items-center justify-between text-white shadow-md sticky top-0 z-50" role="navigation">
        <div class="flex items-center gap-2 overflow-hidden">
            <img src="img/logo.svg" alt="Logo" class="w-32 h-8 sm:w-40 sm:h-10 rounded object-contain shrink-0">
            
            <div class="leading-tight hidden sm:block border-l border-white/20 pl-2">
                <p class="text-[10px] opacity-80 uppercase tracking-tighter">Prefeitura</p>
                <h1 id="pageTitle" class="font-bold text-xs whitespace-nowrap">Meus Protocolos</h1>
            </div>
        </div>

        <a href="logout.php" 
            class="bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0 whitespace-nowrap ml-2 shadow-sm active:scale-95"
            aria-label="Sair do sistema">
            Sair
        </a>
    </nav>

    <main id="listaSection" class="p-4 max-w-md mx-auto">
        <button id="btnFiltro" 
                aria-pressed="false"
                class="bg-[#6489C5] text-white px-4 py-3 rounded-xl flex items-center gap-2 mb-6 w-full justify-center font-medium shadow-md active:scale-95 transition-all">
            <i class="fas fa-filter" aria-hidden="true"></i> 
            <span>Ocultar Concluídos</span>
        </button>

        <section class="space-y-4" aria-labelledby="pageTitle">
            <?php foreach($protocolos as $p): ?>
            <article class="protocolo-card bg-white p-5 rounded-2xl card-shadow border border-gray-100" data-status="<?= $p['status'] ?>" tabindex="0">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-bold text-gray-900 text-lg">Protocolo #<?= $p['id'] ?></h2>
                    <time datetime="<?= date('Y-m-d') ?>" class="text-xs text-gray-500 font-medium"><?= $p['data'] ?></time>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-600">Status: <strong class="text-gray-800"><?= $p['status'] ?></strong></p>
                    <div class="w-3 h-3 rounded-full <?= $p['cor'] ?> ring-4 ring-gray-50" aria-hidden="true"></div>
                </div>
            </article>
            <?php endforeach; ?>
        </section>
    </main>

    <main id="formSection" class="p-4 max-w-md mx-auto hidden">
        <div class="bg-white p-6 rounded-2xl card-shadow border border-gray-100">
            
            <?php if($mostrarMensagem): ?>
            <div id="msgSucesso" role="alert" aria-live="assertive"
                class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl mb-4 text-center font-semibold relative">
                Solicitação enviada com sucesso!
                <button onclick="this.parentElement.style.display='none'" class="absolute top-2 right-3 p-1" aria-label="Fechar mensagem">✕</button>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5" id="formSolicitacao">
                <div>
                    <label for="assunto" class="block text-sm font-medium text-gray-700 mb-1">Assunto da solicitação</label>
                    <input type="text" name="assunto" id="assunto" required
                        placeholder="Ex: Lâmpada queimada na rua" 
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1A5FB4] outline-none">
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de serviço</label>
                    <select name="tipo" id="tipo" required
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1A5FB4]">
                        <option value="">Selecione uma opção</option>
                        <option value="iluminacao">Iluminação Pública</option>
                        <option value="buraco">Tapa-buraco</option>
                        <option value="limpeza">Limpeza Urbana</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição detalhada</label>
                    <textarea name="descricao" id="descricao" rows="4" required placeholder="Forneça detalhes como endereço ou pontos de referência..." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1A5FB4] outline-none"></textarea>
                </div>

                <button type="button" class="w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-medium flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 hover:bg-gray-200 transition">
                    <i class="fas fa-file-upload" aria-hidden="true"></i> Anexar Foto ou Arquivo
                </button>

                <button type="submit" class="w-full py-4 bg-[#1A5FB4] text-white rounded-xl font-bold shadow-lg hover:bg-[#154a8d] transition-transform active:scale-[0.98]">
                    Enviar Solicitação
                </button>
            </form>
        </div>
    </main>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around p-3 shadow-2xl" role="tablist">
        <button onclick="showSection('lista')" id="tabLista" role="tab" aria-selected="true" aria-controls="listaSection" class="flex flex-col items-center text-[#1A5FB4] transition-colors">
            <i class="fas fa-list-ul text-xl" aria-hidden="true"></i>
            <span class="text-[11px] font-bold mt-1">Protocolos</span>
        </button>
        <button onclick="showSection('form')" id="tabForm" role="tab" aria-selected="false" aria-controls="formSection" class="flex flex-col items-center text-gray-400 hover:text-[#1A5FB4] transition-colors">
            <i class="fas fa-plus-circle text-xl" aria-hidden="true"></i>
            <span class="text-[11px] font-bold mt-1">Novo</span>
        </button>
    </nav>

    <script>
        function showSection(section) {
            const lista = document.getElementById('listaSection');
            const form = document.getElementById('formSection');
            const title = document.getElementById('pageTitle');
            const tabLista = document.getElementById('tabLista');
            const tabForm = document.getElementById('tabForm');

            if (section === 'lista') {
                lista.classList.remove('hidden');
                form.classList.add('hidden');
                title.innerText = "Meus Protocolos";
                
                tabLista.setAttribute('aria-selected', 'true');
                tabLista.classList.replace('text-gray-400', 'text-[#1A5FB4]');
                tabForm.setAttribute('aria-selected', 'false');
                tabForm.classList.replace('text-[#1A5FB4]', 'text-gray-400');
            } else {
                lista.classList.add('hidden');
                form.classList.remove('hidden');
                title.innerText = "Nova Solicitação";

                tabForm.setAttribute('aria-selected', 'true');
                tabForm.classList.replace('text-gray-400', 'text-[#1A5FB4]');
                tabLista.setAttribute('aria-selected', 'false');
                tabLista.classList.replace('text-[#1A5FB4]', 'text-gray-400');
            }
        }
        
        document.getElementById('btnFiltro').addEventListener('click', function() {
            const cards = document.querySelectorAll('.protocolo-card');
            const isPressed = this.getAttribute('aria-pressed') === 'true';
            
            this.setAttribute('aria-pressed', !isPressed);
            this.querySelector('span').innerText = isPressed ? "Ocultar Concluídos" : "Mostrar Todos";

            cards.forEach(card => {
                if(card.dataset.status === 'Concluído') {
                    card.classList.toggle('hidden');
                }
            });
        });
        
        <?php if($mostrarMensagem): ?>
            showSection('form');
            document.getElementById('formSolicitacao').reset();
            setTimeout(() => {
                const msg = document.getElementById('msgSucesso');
                if (msg) msg.style.opacity = '0';
                setTimeout(() => msg?.remove(), 500);
            }, 5000);
        <?php else: ?>
            showSection('lista');
        <?php endif; ?>
    </script>
</body>
</html>