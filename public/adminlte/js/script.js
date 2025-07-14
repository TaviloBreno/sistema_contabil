document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // CNPJ auto-preenchimento
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('blur', async function () {
            const cnpj = this.value.replace(/\D/g, '');
            if (cnpj.length !== 14) return;

                const response = await fetch(`https://receitaws.com.br/v1/cnpj/${cnpj}`);
                const data = await response.json();

                if (data.status === 'OK') {
                    document.getElementById('razao_social').value = data.nome || '';
                    document.getElementById('telefone').value = data.telefone || '';
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('regime_tributario').value = data.opcao_pelo_simples ? 'Simples Nacional' : 'Outro';
                } else {
                    alert('CNPJ não encontrado.');
                }
        });
    }
});
