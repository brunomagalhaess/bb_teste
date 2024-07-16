<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <title>Teste</title>
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <link rel="manifest" href="/site.webmanifest">
    <script type="text/javascript" src="js/jquery.js"></script>
    <?php include "config.php"; 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
</head>

<body>
    <div class="header">
        <table class="header_table">
            <tr>
                <td>
                    <a href="index.html"><img src="./img/home.png" alt="Homepage" class="btn_header_img" id="btn_home_img"><p>Homepage</p></a>
                </td>
                <td>
                    <a href="cadastrar.php"><img src="./img/register.png" alt="Cadastrar Cliente" class="btn_header_img" id="btn_search_img"><p>Cadastrar Cliente</p></a>
                </td>
            </tr>
        </table>
    </div>

    <div class="container" style="position:inline;">
        <div class="container_content">
            <section class="container_header">
                <h3>Clientes Cadastrados</h3>
            </section>
            <div id="div_busca" class="form-content">
                <form action="#" method="POST" name="formConsulta" class="form">
                    <table class="form-table">
                        <tr>
                            <td width="30vw" >Nome:</td>
                            <td ><input type="text" name="nome" id="buscar_nome" size="40%" /></td>
                        </tr>
                        <tr>
                            <td >CPF:</td>
                            <td ><input type="text" name="cpf" id="buscar_cpf" size="40%"/></td>
                        </tr>
                    </table>
                    <input class="btn_consulta" type="button" name="botao" value="Buscar" id="btn_buscar"></input>
                </form>
            </div>
        <div>
    </div>


    <script src="js/script.js"></script>

    <script>

        $(document).ready(function (e) {
            
            $('#btn_buscar').click(function(){
                $.ajax({
                    type: "POST",
                    url: "funcoes.php",
                    data: {                        
                        consulta_cpf: $("#buscar_cpf").val(),
                        consulta_nome: $("#buscar_nome").val(),
                        action: 'buscarEspecifico'
                    },
                }).done(function (response) {
                    $("#resultado_busca").html(response);                    
                });

            });

        });

    </script>


    <div class="div_cadastrar_listagem" style="display:block; max-width: 450px;">
        <div id="resultado_busca" class="form-content">
        </div>
    </div>
    
</body>
</html>
