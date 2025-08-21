let deleteUrl = '';

function openDeleteModal(url, entityName) {
    deleteUrl = url;
    document.getElementById('deleteModalLabel').textContent = `Excluir ${entityName}`;
    document.getElementById('deleteModalMessage').textContent = `Tem certeza que deseja excluir este ${entityName}?`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!deleteUrl) return;

    try {
        const res = await fetch(deleteUrl, {
            method: 'POST', // ou DELETE se sua rota aceitar
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!res.ok) throw new Error('Erro ao excluir');

        // Fecha o modal
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();

        // Atualiza tabela (pode ser recarregando via ajax ou removendo a linha)
        location.reload(); // mais simples: recarrega a página/tabela
    } catch (err) {
        alert('Erro ao excluir.');
        console.error(err);
    }
});

