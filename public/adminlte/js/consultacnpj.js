document.getElementById("cnpj").addEventListener("blur", async function () {
    let cnpj = this.value.replace(/\D/g, "");

    if (!/^\d{14}$/.test(cnpj)) {
        alert("CNPJ inválido. Informe 14 números.");
        return;
    }

    try {
        const res = await fetch(`/consulta-cnpj?cnpj=${cnpj}`);
        if (!res.ok) throw new Error("Erro na resposta da API");

        const data = await res.json();

        if (data.error || data.status !== "OK") {
            alert(data.error || "Erro ao consultar o CNPJ.");
            return;
        }

        // Preenche os campos
        const fill = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.value = value || "";
        };

        fill("razao_social", data.nome);
        fill("fantasia", data.fantasia);
        fill("abertura", data.abertura);
        fill("telefone", data.telefone);
        fill("email", data.email);
        fill("natureza_juridica", data.natureza_juridica);
        fill("porte", data.porte);
        fill("tipo", data.tipo);
        fill("logradouro", data.logradouro);
        fill("numero", data.numero);
        fill("complemento", data.complemento);
        fill("bairro", data.bairro);
        fill("municipio", data.municipio);
        fill("uf", data.uf);
        fill("cep", data.cep);
        fill("capital_social", data.capital_social);
        fill("situacao", data.situacao);
        fill("data_situacao", data.data_situacao);
        fill("situacao_especial", data.situacao_especial);
        fill("data_situacao_especial", data.data_situacao_especial);
        fill("regime_tributario", data.natureza_juridica); // campo usado como substituto

        // Sócios
        const socios = (data.qsa || []).map((s) => s.nome).join(", ");
        fill("socios", socios);
    } catch (err) {
        console.error("Erro ao consultar CNPJ:", err);
        alert("Não foi possível consultar esse CNPJ no momento.");
    }
});
