<h2>Resumo de Obrigações</h2>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Pendente</td>
        <td>{{ $pendentes }}</td>
    </tr>
    <tr>
        <td>Em Andamento</td>
        <td>{{ $emAndamento }}</td>
    </tr>
    <tr>
        <td>Concluída</td>
        <td>{{ $concluidas }}</td>
    </tr>
    </tbody>
</table>

@if(!empty($graficoUrl))
    <h2 style="margin-top: 30px;">Gráfico de Obrigações por Status</h2>
    <img src="{{ $graficoUrl }}" alt="Gráfico de Obrigações" style="width:100%; height:auto;">
@endif
