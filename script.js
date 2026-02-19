function showSection(section) {
    const lista = document.getElementById('listaSection');
    const form = document.getElementById('formSection');
    const title = document.getElementById('pageTitle');
    
    if (section === 'lista') {
        lista.classList.remove('hidden');
        form.classList.add('hidden');
        title.innerText = "Meus Protocolos";
    } else {
        lista.classList.add('hidden');
        form.classList.remove('hidden');
        title.innerText = "Nova Solicitação";
    }
    
    title.setAttribute('tabindex', '-1'); 
    title.focus();

    window.scrollTo(0, 0);
}

document.getElementById('btnFiltro').addEventListener('click', function() {
    const cards = document.querySelectorAll('.protocolo-card');
    const isPressed = this.getAttribute('aria-pressed') === 'true';
    this.setAttribute('aria-pressed', !isPressed);
    this.classList.toggle('bg-blue-800'); 
    
    cards.forEach(card => {
        if (card.dataset.status === 'Concluído') {
            card.classList.toggle('hidden');
        }
    });
    
    if (!isPressed) {
        this.innerHTML = '<i class="fas fa-eye" aria-hidden="true"></i> Mostrar Todos';
    } else {
        this.innerHTML = '<i class="fas fa-filter" aria-hidden="true"></i> Filtrar Concluídos';
    }
});

const params = new URLSearchParams(window.location.search);
if (params.get("enviado") === "1") {
    showSection('form');
    window.history.replaceState({}, document.title, window.location.pathname);
}