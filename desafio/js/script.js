var ajaxReq = new XMLHttpRequest();
ajaxReq.open("POST", "cadastro.php", true);
ajaxReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

ajaxReq.onreadystatechange = function () {
    if (this.readyState === 4) {
        console.log(ajaxReq.responseText);
    }
};


$(document).ready(function () {
    $("form").submit(function (event) {
        var rendaInformada = $("#id-renda").val();
        const gravar = conferirRenda(rendaInformada);

        if (gravar) {

            var formDataIncluir = {
                cpf: $("#id-cpf").val(),
                nome: $("#id-nome").val(),
                dtnasc: $("#id-dtnasc").val(),
                matricula: $("#id-matricula").val(),
                renda: $("#id-renda").val(),
                just_renda: $("#id-just-renda").val(),
                incluir: true
            };

            $.ajax({
                type: "POST",
                url: "cadastro.php",
                data: formDataIncluir,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                console.log(data);
            });
        }
        event.preventDefault();
    });  
});

function abrirPagina(Pagina) {
    if ((Pagina == "novoCadastro")) {
        window.location = "cadastrar.php";
    }
    if ((Pagina == "abrirCadastro")) {
        window.location = "consulta.php";
    }
};

   
function conferirRenda(renda) {

    const salarioMinimo = 1500;
    const divJustificarRenda = document.getElementsByClassName(
        "div-justificar-renda"
    );
    const inputJustificarRenda = document.getElementById("id-just-renda");

    if (renda != "" && renda < salarioMinimo && inputJustificarRenda.value == "") {
        alert("R$"+renda+" menor que o Salário Mínimo vigente. Favor, justificar."
        );
        $("#td_renda")
            .removeClass("form-table-input")
            .addClass("form-input-alerta");

        if ((divJustificarRenda[0].className = "div-justificar-renda")) {
            divJustificarRenda[0].className = "form";
            $("#td_justrenda").css({ border: "1px solid crimson"});
        }
    }

    if (renda >= salarioMinimo || (renda < salarioMinimo && inputJustificarRenda.value != "")) {
        paginaCadastrar_btn_cadastrar();
    }
};


function paginaCadastrar_btn_cadastrar() {

    var dtnasc1 = $("#id-dtnasc").val();
    dtnasc = new Date(dtnasc1);
    var dataHoje = new Date();
    var dataMiliseg = dataHoje.getTime() - dtnasc.getTime();
    var idade = Math.round(Math.abs(dataMiliseg / (365 * 24 * 60 * 60 * 1000)));
    
        $.ajax({
            type: "POST",
            url: "./funcoes.php",
            data: {
                cpf: $("#id-cpf").val(),
                nome: $("#id-nome").val(),
                dtnasc: $("#id-dtnasc").val(),
                idade: idade,
                matricula: $("#id-matricula").val(),
                renda: $("#id-renda").val(),
                just_renda: $("#id-just-renda").val(),
                action: "btn_cadastrar"
            },
        }).done(function (response) {
            alert("Cliente cadastrado com sucesso"); //retorna a inserção do cliente no Bando de Dados
            $("#form")[0].reset();
            $(".div_cadastrar_listagem")
                .css("display", "block")
                .html(response);
            $(".div_cadastrar_listagem");
        });
    return false;
};
        