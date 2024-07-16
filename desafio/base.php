<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <title>Teste</title>
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <link rel="manifest" href="/site.webmanifest">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">$(function(){})</script>
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
    <div class="container">
        <div id="relatorioDiv" class="form-content">
            
        
        </div>

    <script src="js/script.js"></script>
    <script>

        $(document).ready(function (e) {  
            $.ajax({
                type: "POST",
                url: "funcoes.php",
                data: {
                    action: 'buscarTudo'
                },
            }).done(function (response) {
                $(".form-content").html(response);                
            });
        });


    </script>

    </div>

</body>

</html>
