<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>Teste</title>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript"></script>
        
</head>

<body>
    <div class="header">
        <table class="header_table">
            <tr>
                <td>
                    <a href="index.html"><img src="./img/home.png" alt="Homepage" class="btn_header_img" id="btn_home_img"><p>Homepage</p></a>
                </td>
                <td>
                    <a href="consulta.php"><img src="./img/search.png" alt="Consultar Clientes" class="btn_header_img" id="btn_search_img"><p>Consultar Clientes</p></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="container">

        <div class="container_content">
        <section class="container_header">
            <h3>Cadastrar Cliente</h3>
        </section>

        <form id="form" class="form" name="formulario">
            <div class="form-content">
                <table class="form-table">
                    <tr>
                        <td>
                            <span id="cpf">CPF do Cliente</span>
                        </td>
                        <td class="form-table-input">
                            <input type="text" name="cpf" id="id-cpf" placeholder="Digite o CPF do Cliente" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="nome">Nome Completo</span>
                        </td>
                        <td class="form-table-input">
                            <input type="text" name="nome" id="id-nome" placeholder="Digite o Nome do Cliente" oninput="this.value = this.value.toUpperCase()" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="dtnasc">Data de Nascimento</span>
                        </td>
                        <td class="form-table-input">
                            <input type="date" name="dtnasc" id="id-dtnasc" placeholder="Data de Nasc" required>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <span id="matricula">Matrícula</span>
                        </td>
                        <td class="form-table-input">
                            <input type="text" name="cpf" id="id-matricula" placeholder="Digite a Matrícula" oninput="this.value = this.value.toUpperCase()">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="renda">Renda (em R$)</span>
                        </td>
                        <td class="form-table-input" id="td_renda">
                            <input type="number" step="0.01" name="renda" id="id-renda" placeholder="Valor da Renda do Cliente">
                        </td>
                    </tr>
                </table>
                <div class="div-justificar-renda">
                    <table class="form-table">
                        <tr>
                            <td>
                                <span id="justificar_renda">Justificar Renda</span>
                            </td>
                            <td class="form-table-input" id="td_justrenda">
                                <textarea name="justificar_renda" id="id-just-renda" cols="29" placeholder="Renda informarda menor que o Salário Mínimo. Favor justificar."></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <button type="button" id="btn_cadastrar" > <p id="btn_cadastrar_p">Cadastrar</p> </button>
                <button type="button" id="btn_cancelar_update" > <p id="btn_cancelar_update_p"> &#10060; </p> </button>

                <input type="hidden" name="cad_id" id="id_cad_id" value="valor_id">
            </div>

        </form>
        </div>

            <div class="div_cadastrar_listagem">
            </div>

    </div>


    <script src="js/script.js"></script>

    <script>

        var click_id;
        var update = false;

        $(document).ready(function (e) {
            $('#id-cpf').focus().keyup(function(){
                if($(this).val().length == 11){
                    $('#id-nome').focus();
                }
            });
            
            $('#btn_cadastrar').click(function(){
                if(!update){
                    if($('#id-cpf').val() == ""){//se os campos estão vazios e o botão Cadastrar foi acionado serão listados todos os clientes
                        $.ajax({
                            type: "POST",
                            url: "funcoes.php",
                            data: {
                                action: 'imprimirTodos'
                            },
                        }).done(function (response) {
                            $(".div_cadastrar_listagem").css("display", "block").html(response);
                            $(".div_cadastrar_listagem");
                        });
                    }else{//se houver conteúdo no formulário será acionado o processo de inclusão de cliente
                        conferirRenda($("#id-renda").val());
                    };
                };
                if(update){//atualiza os dados de um cliente já cadastrado
                    if($('#id-cpf').val() != ""){
                        $.ajax({
                            type: "POST",
                            url: "funcoes.php",
                            data: {
                                cad_id: $("#id_cad_id").val(),
                                cad_cpf: $("#id-cpf").val(),
                                cad_nome: $("#id-nome").val(),
                                cad_dtnasc: $("#id-dtnasc").val(),
                                cad_matricula: $("#id-matricula").val(),
                                cad_renda: $("#id-renda").val(),                                
                                action: 'atualizarCadastro'
                            },
                        }).done(function (response) {
                            $(".div_cadastrar_listagem").css("display", "block").html(response);
                            $(".div_cadastrar_listagem");
                        });
                    };
                };
            });

            $('#btn_cancelar_update').click(function(){//cancela a opção de Update do cliente, limpando o formulário
                location.reload(true);
            });
        });


        document.body.addEventListener('dblclick', event => {

            if(event.target.id == "cad_id"){//se duplo-clique em campo da coluna ID da tabela, será questionado se deseja apagar o cliente correspondente

                let click_id = event.target.innerText;
                let click_nome_td = event.target.nextElementSibling;
                let click_nome = event.target.nextElementSibling.innerText;
                let click_cpf = click_nome_td.nextElementSibling.innerText;
                let escolha = prompt("Confirma a exclusão de "+click_nome+" (CPF "+click_cpf+")?\nEsta ação não poderá ser revertida!.\n\nDigite 'SIM' ou 'NÃO':").toUpperCase();

                if(escolha == "SIM"){//caso digite SIM inicia o DELETE do cliente; qualquer outra resposta não tem ação
                    $.ajax({
                        type: "POST",
                        url: "funcoes.php",
                        data: {
                            cad_id:click_id,
                            cad_nome:click_nome,
                            cad_cpf:click_cpf,
                            action:"botao_conf_exclusao"
                        },
                    }).done(function (response) {
                        $(".div_cadastrar_listagem").css("display", "block").html(response);
                        $(".div_cadastrar_listagem");
                    });
                };
            };

            
            if(event.target.id == "cad_nome_table"){//se duplo-clique em campo da coluna NOME da tabela, os dados do cliente correspondente são carregados no formulário e a  variável global 'update' é validada
                var click_id = event.target.previousElementSibling.innerText;
                $('#id_cad_id').attr("value", click_id);

                var click_nome = event.target.innerText;
                $('#id-nome').attr("value", click_nome);

                let click_cpf_td = event.target.nextElementSibling;
                var click_cpf = click_cpf_td.innerText;
                $('#id-cpf').attr("value", click_cpf);

                let click_dtnasc_td = click_cpf_td.nextElementSibling;
                var click_dtnasc = click_dtnasc_td.innerText;
                let formatarData = click_dtnasc.split("/");
                let dataFormatada = (formatarData[2]+"-"+formatarData[1]+"-"+formatarData[0]);
                $('#id-dtnasc').attr("value", dataFormatada);
                
                let click_idade_td = click_dtnasc_td.nextElementSibling;
                var click_idade = click_idade_td.innerText;
                
                let click_matricula_td = click_idade_td.nextElementSibling;
                var click_matricula = click_matricula_td.innerText;
                $('#id-matricula').attr("value", click_matricula);
                
                let click_renda_td = click_matricula_td.nextElementSibling;
                var click_renda = parseFloat(click_renda_td.innerText.replace(".","").slice(3,));
                $('#id-renda').attr("value", click_renda);

                
                $("#btn_cadastrar_p").html("Atualizar Cadastro");
                $("#btn_cancelar_update").css("display", "inline");
                update = true;

            };
        });

    </script>

</body>

</html>