<?php
header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
header('Content-Type: text/javascript');
include ('config.php');
session_start();



if(isset($_POST['action'])){

    $action = $_POST['action'];
    
    switch ($action) {

        case 'imprimirTodos':
            imprimirTodos(); // monta tabela com os clientes do DB
            break;

        case 'btn_cadastrar':
            btn_cadastrar(); // insere novo cliente no BD
            break;

        case 'botao_conf_exclusao':
            botao_conf_exclusao(); // apaga cliente no BD
            break;

        case 'atualizarCadastro':
            atualizarCadastro(); // atualiza os dados de um cliente selecionado
            break;

        case 'buscarTudo':
            buscarTudo(); // busca os clientes no BD e monta tabela
            break;

        case 'buscarEspecifico':
            buscarEspecifico(); // busca um cliente por Nome ou CPF
            break;

    };
};


function imprimirTodos (){

    echo '<table class="div_cadastrar_listagem_table">

        <thead>
            <tr>
            <th colspan="7"> <h3> CLIENTES CADASTRADOS </h3> </th>
            </tr>
        </thead>
        <tr style="background-color:#fff; color:black">
            <th >Id</th>
            <th width="33%">Nome</th>
            <th width="">CPF</th>
            <th width="">Nascimento</th>
            <th width="">Idade</th>
            <th width="">Matrícula</th>
            <th width="">Renda</th>
        </tr>
        <tbody>';

    
    $con = mysqli_connect('localhost','root','');
    $db = mysqli_select_db($con, 'db_teste_bb');
    
    $query = "SELECT * 
            FROM cadastro 
            ORDER by cad_id
            ";
    $result = mysqli_query($con, $query);
    if(!$result) echo mysqli_error($con);
    
    $linha = 0;
    $rendatotal = 0;

    while ($coluna=mysqli_fetch_array($result)) {
        
        if ($linha %2 == 0) $cor = "#d9dcdd"; else $cor = "white";
        $linha++;

        $dataNasc = date_create($coluna['cad_dtnasc']);
        $dataNascFormated = date_format($dataNasc,"d/m/Y");

        echo
            '<tr>
                <th class="cad_id" id="cad_id">'.     $coluna['cad_id']    .'</th>
                <td id="cad_nome_table" width=""><b>'.     $coluna['cad_nome'] .'</b></td>
                <td width="">'.     $coluna['cad_cpf']   .'</td>
                <td width="">'.     $dataNascFormated    .'</td>
                <td width="">'.     $coluna['cad_idade'] .'</td>
                <td width="">'.     $coluna['cad_matricula']    .'</td>
                <td width="">R$ '.   number_format($coluna['cad_renda'],2,",",".")   .'</td>
            </tr>';

    $rendatotal = $rendatotal + $coluna['cad_renda'];
    };
    $rendatotal = (float)$rendatotal;
    echo '
    </tbody>
    <tfoot width="100%">
        <tr style="font-size:13px">
        <td colspan="4" style="background-color:#fff"></td>
        <td colspan="2" style="color:#000; background-color:#fff; text-align:right;"> <b> Renda Total &ensp;</b> </td>
        <td style="color:#000; background-color:#fcfc30; text-align:center;"><b>R$ '. number_format($rendatotal,2,",",".") .'</b></td>
        </tr>
    </tfoot>
    </table>
    <div id="div_cadastrar_listagem_obs"><span>* Duplo-clique no Nome de um cliente para alterar os dados<br>
    * Duplo-clique no ID de um cliente para excluir seu cadastro</span></div>    
    ';
    
};


function btn_cadastrar(){
    include ('config.php');
    
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $dtnasc = $_POST['dtnasc'];
    $idade = $_POST['idade'];
    $matricula = $_POST['matricula'];
    $renda = $_POST['renda'];
    $just_renda = $_POST['just_renda'];

    $insere = "INSERT into 
                cadastro (cad_cpf, cad_nome, cad_dtnasc, cad_idade, cad_matricula, cad_renda, cad_justrenda) 
                VALUES ('$cpf', '$nome', '$dtnasc', $idade, '$matricula', '$renda', '$just_renda')";
    $result_insere = mysqli_query($con, $insere);
    
    if ($result_insere) {
        
    } else {
        echo "<b>Falha no cadastro. Favor, refazer operação.</b>";
    } echo mysqli_error($con);

    imprimirTodos();

}die();


function atualizarCadastro(){
    include ('config.php');

    $cad_id = $_POST['cad_id'];
    $cad_cpf = $_POST['cad_cpf'];
    $cad_nome = $_POST['cad_nome'];
    $cad_dtnasc = $_POST['cad_dtnasc'];
    $cad_matricula = $_POST['cad_matricula'];
    $cad_renda = $_POST['cad_renda'];

    $update = "UPDATE cadastro 
            SET cad_cpf = '$cad_cpf',
                cad_nome = '$cad_nome',
                cad_dtnasc = '$cad_dtnasc',
                cad_matricula = '$cad_matricula',
                cad_renda = '$cad_renda'
            WHERE cad_id = '$cad_id'
            ";
    $result_update = mysqli_query($con, $update);
    if(!$result_update){
        echo mysqli_error ($con);
    }else{
        echo "<script>alert('Cadastro Atualizado!');</script>";
    }

}die();



function botao_conf_exclusao(){
    include ('config.php');
    $cad_id = $_POST['cad_id'];
    
    $query_excluir = "DELETE FROM cadastro 
        WHERE cad_id = '$cad_id'
    ";
    $result_excluir = mysqli_query($con, $query_excluir);
    
    if ($result_excluir) echo "<h2> Cliente excluído dos registros. </h2>";
    else echo "<h2> Não foi possível efetuar a exclusão</h2>";
    echo mysqli_error($con);

    imprimirTodos();

}die();


function buscarTudo() { 
    include ('config.php'); ?>
    <table class="div_cadastrar_listagem_table">
        <tr >
            <th >Cód</th>
            <th width="40%">Nome</th>
            <th width="12%">CPF</th>
            <th width="12%">Nascimento</th>
            <th width="12%">Matrícula</th>
            <th width="12%">Renda</th>
        </tr>

    <?php
    
    $query = "SELECT * 
            FROM cadastro 
            WHERE cad_id > 0 ";
    $query .= " ORDER by cad_id";
    $result = mysqli_query($con, $query);
    if(!$result) echo mysqli_error($con);
    
    $linha = 0;

    while ($coluna=mysqli_fetch_array($result)) {
        
        if ($linha %2 == 0) $cor = "#d9dcdd"; else $cor = "white";
        $linha++;
        ?>
            <tr>
                <th id="cad_id">    <?php echo $coluna['cad_id']; ?>        </th>
                <td width="40%">    <?php echo $coluna['cad_nome']; ?>      </td>
                <td width="12%">    <?php echo $coluna['cad_cpf']; ?>       </td>
                <td width="12%">    <?php echo $coluna['cad_dtnasc']; ?>    </td>
                <td width="12%">    <?php echo $coluna['cad_matricula']; ?> </td>
                <td width="12%">    <?php echo $coluna['cad_renda']; ?>     </td>
                <td> <button type="submit" id="editar_<?php echo $coluna['cad_id']; ?>"> Editar </button> </td>
                <td> <button type="submit" id="excluir_<?php echo $coluna['cad_id']; ?>"> Excluir </button> </td>
            </tr>
    <?php
    }
}



function buscarEspecifico() {
    include ('config.php'); ?>

        <table class="table_consulta">
            <thead>
                <tr >
                    <th width="">Nome</th>
                    <th >CPF</th>
                    <th >Nascimento</th>
                    <th >Matrícula</th>
                    <th >Renda</th>
                </tr>
            </thead>

        <?php

        $nome = @$_POST['consulta_nome'];
        $cpf = @$_POST['consulta_cpf'];
        
        $query = "SELECT * 
                FROM cadastro 
                WHERE cad_id > 0 ";
        $query .= ($nome ? " AND cad_nome LIKE '%$nome%' " : "");
        $query .= ($cpf ? " AND cad_cpf LIKE '%$cpf%' " : "");
        $query .= " ORDER by cad_id";
        $result = mysqli_query($con, $query);
        if(!$result) echo mysqli_error($con);
        
        $linha = 0;

        while ($coluna=mysqli_fetch_array($result)) {

            

        $dataNasc = date_create($coluna['cad_dtnasc']);
        $dataNascFormated = date_format($dataNasc,"d/m/Y");
            
            if ($linha %2 == 0) $cor = "#d9dcdd"; else $cor = "white";
            $linha++;
            ?>
                <tr>
                    
                <form action="#" method="POST" name="formConsultaEditar" class="form">
                    <input type="hidden" name="cad_id" value="<?php echo $coluna['cad_id']; ?>">

                    <td id="cad_nome" class="table_consulta_input" > <input type="hidden" name="cad_nome" value="<?php echo $coluna['cad_nome']; ?>">    <?php echo $coluna['cad_nome']; ?>      </td>

                    <td id="cad_cpf" class="table_consulta_input" > <input type="hidden" name="cad_cpf" value="<?php echo $coluna['cad_cpf']; ?>">   <?php echo $coluna['cad_cpf']; ?>       </td>

                    <td id="cad_dtnasc" class="table_consulta_input" > <input type="hidden" name="cad_dtnasc" value="<?php echo $coluna['cad_dtnasc']; ?>">   <?php echo $dataNascFormated; ?>    </td>

                    <td id="cad_matricula" class="table_consulta_input" >  <input type="hidden" name="cad_matricula" value="<?php echo $coluna['cad_matricula']; ?>">  <?php echo $coluna['cad_matricula']; ?> </td>

                    <td id="cad_renda" class="table_consulta_input" >  <input type="hidden" name="cad_renda" value="<?php echo $coluna['cad_renda']; ?>">  <?php echo $coluna['cad_renda']; ?>     </td>
                </form>
                </tr>
            <?php
            }
            ?>
        </table>
<?php
}die();

if ($_POST['botao_editar']) {
    $cad_id = intval($_POST['cad_id']);

    ?>
    <div class="form-content">
        <form action="#" method="POST" name="formConsultaEditar" class="form" id="confEditForm">
        <table class="form-table">
            <tr><td><label for="cpf">CPF do Cliente</label></td>
                <td class="form-table-input">
                    <input type="text" id="id-editar-cpf" name="cad_cpf" value="<?php echo $_POST['cad_cpf']; ?>" placeholder="Digite o CPF do Cliente">
                </td>
            </tr>
            <tr><td><label for="nome">Nome Completo</label></td>
                <td class="form-table-input">
                    <input type="text" id="id-editar-nome" name="cad_nome" value="<?php echo $_POST['cad_nome']; ?>"  placeholder="Digite o Nome do Cliente">
                </td>
            </tr>
            <tr><td><label for="dtnasc">Data de Nascimento</label></td>
                <td class="form-table-input">
                    <input type="date" id="id-editar-dtnasc" name="cad_dtnasc" value="<?php echo $_POST['cad_dtnasc']; ?>" placeholder="Data de Nasc">
                </td>
            </tr>                    
            <tr><td><label for="matricula">Matrícula</label></td>
                <td class="form-table-input">
                    <input type="text" id="id-editar-matricula" name="cad_matricula" value="<?php echo $_POST['cad_matricula']; ?>" placeholder="Digite a Matrícula">
                </td>
            </tr>
            <tr><td><label for="renda">Renda (em R$)</label></td>
                <td class="form-table-input" id="td_renda">
                    <input type="number" step="0.01" id="id-editar-renda" name="cad_renda" value="<?php echo $_POST['cad_renda']; ?>" placeholder="Valor da Renda do Cliente">
                </td>
            </tr>            
            <tr>
                <td>
                    <label for="justificar_renda">Justificar Renda</label>
                </td>
                <td class="form-table-input" id="td_justrenda">
                    <textarea name="cad_justrenda" id="cad_justrenda" cols="29" placeholder="Justificar Renda" form="confEditForm"></textarea>
                </td>
            </tr>
        </table>
        
            <input type="hidden" name="cad_id" value="<?php echo $_POST['cad_id']; ?>">
            <input type="submit" class="btn_consulta_edit" name='botao_confirma_edit' alt="Confirmar Edição" value="Confirmar Edição" class="input_img_editar" id="confirma_editar_<?php echo $_POST['cad_id']; ?>" > </input>
        </form>
    </div>

<?php
}

if ($_POST['botao_confirma_edit']) {
    $cad_id = $_POST['cad_id'];
    $cad_cpf = $_POST['cad_cpf'];
    $cad_nome = $_POST['cad_nome'];
    $cad_dtnasc = $_POST['cad_dtnasc'];
    $cad_matricula = $_POST['cad_matricula'];
    $cad_renda = $_POST['cad_renda'];

    $update = "UPDATE cadastro 
            SET cad_cpf = '$cad_cpf',
                cad_nome = '$cad_nome',
                cad_dtnasc = '$cad_dtnasc',
                cad_matricula = '$cad_matricula',
                cad_renda = '$cad_renda'
            WHERE cad_id = '$cad_id'
            ";
    $result_update = mysqli_query($con, $update);
    if(!$result_update){
        echo mysqli_error ($con);
    }else{
        echo "<script>alert('Cadastro Atualizado!');</script>";
    }
}

if($_POST['botao_excluir']) {
    
    $cad_id = $_POST['cad_id'];
    $cad_cpf = $_POST['cad_cpf'];
    $cad_nome = $_POST['cad_nome'];

    ?>
    <div class="container">
        <p text-align="center">Cliente <?php echo $cad_nome ?> será excluído! Confirma?</p>
        <form action="#" method="POST" name="conf_exclusao" class="form">
            <input type="hidden" name="cad_id" value="<?php echo $cad_id; ?>">
                <input type="submit" name="botao_conf_exclusao" value="Sim" class="input_img_editar" id="conf_excluir_<?php echo $cad_id ?>" alt="Confirma Exclusão" > </input>
                <input type="button" name="botao_conf_exclusao" value="Não" class="input_img_editar" alt="Não" > </input>
        </form>
    </div>
<?php
}


if($imprimirTodos == "true"){
        echo '<table class="div_cadastrar_listagem_table">

            <thead>
                <tr>
                <th colspan="7"> <h3> CLIENTES CADASTRADOS </h3> </th>
                </tr>
            </thead>
            <tr style="background-color:#fff; color:black">
                <th >Id</th>
                <th width="33%">Nome</th>
                <th width="">CPF</th>
                <th width="">Nascimento</th>
                <th width="">Idade</th>
                <th width="">Matrícula</th>
                <th width="">Renda</th>
            </tr>
            <tbody>';
    
        
        $con = mysqli_connect('localhost','root','');
        $db = mysqli_select_db($con, 'db_teste_bb');
        
        $query = "SELECT * 
                FROM cadastro 
                ORDER by cad_id
                ";
        $result = mysqli_query($con, $query);
        if(!$result) echo mysqli_error($con);
        
        $linha = 0;
        $rendatotal = 0;

        while ($coluna=mysqli_fetch_array($result)) {
            
            if ($linha %2 == 0) $cor = "#d9dcdd"; else $cor = "white";
            $linha++;

            $dataNasc = date_create($coluna['cad_dtnasc']);
            $dataNascFormated = date_format($dataNasc,"d/m/Y");

            echo
                '<tr>
                    <th class="cad_id" id="cad_id">'.     $coluna['cad_id']    .'</th>
                    <td id="cad_nome_table" width=""><b>'.     $coluna['cad_nome'] .'</b></td>
                    <td width="">'.     $coluna['cad_cpf']   .'</td>
                    <td width="">'.     $dataNascFormated    .'</td>
                    <td width="">'.     $coluna['cad_idade'] .'</td>
                    <td width="">'.     $coluna['cad_matricula']    .'</td>
                    <td width="">R$ '.   number_format($coluna['cad_renda'],2,",",".")   .'</td>
                </tr>';

        $rendatotal = $rendatotal + $coluna['cad_renda'];
        };
        $rendatotal = (float)$rendatotal;
        echo '
        </tbody>
        <tfoot width="100%">
            <tr style="font-size:13px">
            <td colspan="4" style="background-color:#fff"></td>
            <td colspan="2" style="color:#000; background-color:#fff; text-align:right;"> <b> Renda Total &ensp;</b> </td>
            <td style="color:#000; background-color:#fcfc30; text-align:center;"><b>R$ '. number_format($rendatotal,2,",",".") .'</b></td>
            </tr>
        </tfoot>
        </table>
        <div id="div_cadastrar_listagem_obs"><span>* Duplo-clique no Nome de um cliente para alterar os dados
        <br>* Duplo-clique no ID de um cliente para excluir seu cadastro</span></div>
        
        ';
    };

