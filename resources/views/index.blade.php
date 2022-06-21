@extends('layout.site')

@section('conteudo')
<div class="container">
    <div class="row">
        <div class="col s12 m6">
            <div class="card" style="padding: 4%;">
                <h5>Cadastro cotação de frete</h5>
                <div class="row">
                    <form class="col s12" id="createCotacao" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="input-field col s8">
                                <select id="transportadora_id" name="transportadora_id" style="display: block;">
                                    <option value="" disabled selected>Transportadoras</option>
                                    @foreach($transportadoras as $transportadora)
                                    <option value="{{ $transportadora->id }}"> {{ $transportadora->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s8">
                                <select id="uf" name="uf" style="display: block;">
                                    <option value="" disabled selected>UF</option>
                                    @foreach($ufs as $uf)
                                    <option value="{{ $uf->sigla }}"> {{ $uf->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s8">
                                <input id="percentual_cotacao" name="percentual_cotacao" type="text" class="validate">
                                <label for="percentual_cotacao">Percentual cotação (%)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s8">
                                <input id="valor_extra" name="valor_extra" type="text" class="validate">
                                <label for="valor_extra">Valor extra ($)</label>
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: right;">
                            <button id="button-create" type="button" class="btn deep-orange">Salvar</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col s12 m6">
            <div class="card" style="padding: 4%;">
                <h5>Cotar frete</h5>
                <div class="row">
                    <form class="col s12" id="putCotacao" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="input-field col s8">
                                <select id="uf" name="uf" style="display: block;">
                                    <option value="" disabled selected>UF</option>
                                    @foreach($ufs as $uf)
                                    <option value="{{ $uf->sigla }}"> {{ $uf->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s8">
                                <input id="valor_pedido" name="valor_pedido" type="text" class="validate">
                                <label for="valor_pedido">Valor pedido </label>
                            </div>
                        </div>
                        <div class="row">
                            <button id="button-cotacao" type="button" class="btn deep-orange">Salvar</button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="card" style="padding: 4%;">
                        <h7>Melhores resultados</h7>

                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Transportadora</th>
                                    <th>Valor cotação</th>
                                </tr>
                            </thead>
                            <tbody id="linhas-cotacao">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card" style="padding: 4%;">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>UF</th>
                        <th>Percentual Frete</th>
                        <th>Valor Taxa</th>
                        <th>Transportadora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cotacao as $registro)
                    <tr>
                        <td>{{ $registro->id }}</td>
                        <td>{{ $registro->uf }}</td>
                        <td>{{ $registro->percentual_cotacao }}</td>
                        <td>{{ $registro->extra_value }}</td>
                        <td>{{ $registro->transportadora_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#button-create').on("click", function(e) {
        e.preventDefault();
        var data = $('#createCotacao').serialize();
        $.ajax({
            url: "api/cotacao",
            type: "post",
            dataType: "json",
            data: data,
            success: function(data) {
                alert(data.message);
                window.location.reload();
            },
            error: function(data) {
                alert(data.responseJSON.message)
            }
        });
    })

    $('#button-cotacao').on("click", function(e) {
        e.preventDefault();
        var data = $('#putCotacao').serialize();
        $.ajax({
            url: "api/cotacao",
            type: "put",
            dataType: "json",
            data: data,
            success: function(data) {
                let rank = 1;
                let html = '';
                Object.keys(data).forEach((item) => {
                    if (rank <= 3) {
                        html += "<tr>";
                        html += "<td>" + rank + "</td>";
                        html += "<td>" + data[item].transportadora_id + "</td>";
                        html += "<td>" + data[item].valor_cotacao + "</td>";
                        html += "</tr>";
                        var element = document.getElementById('linhas-cotacao');
                        element.innerHTML = html;
                        rank++;
                    }

                });
            },
            error: function(data) {
                alert(data.responseJSON.message);
                var element = document.getElementById('linhas-cotacao');
                element.innerHTML = '';

            }
        });
    })

});
</script>