document.getElementById('cnpj').addEventListener('blur', async function () {
    const cnpj = this.value.replace(/\D/g, '');
    if (cnpj.length !== 14) return;

    try {
        const response = await fetch(`https://receitaws.com.br/v1/cnpj/${cnpj}`);
        const data = await response.json();

        if (data.status === 'OK') {
            document.getElementById('razao_social').value = data.nome;
            document.getElementById('telefone').value = data.telefone;
            document.getElementById('email').value = data.email;
            document.getElementById('regime_tributario').value = data.opcao_pelo_simples === true ? 'Simples Nacional' : 'Outro';
        } else {
            alert('CNPJ não encontrado.');
        }
    } catch (error) {
        alert('Erro ao buscar dados do CNPJ.');
        console.error(error);
    }
});
