<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload de arquivos</title>
</head>

<body>
<?php

// verifica se foi enviado um arquivo 
if((isset($_FILES['imagem']['name']) && $_FILES["imagem"]["error"] == 0) && ((isset($_FILES['audio']['name']) && $_FILES["audio"]["error"] == 0))
)
{
/////////// NOME //////////////////////////////////

	// Converte o nome  para mimusculo
	$nome = strtolower($_POST['nome']);
				
/////////// POSIÇÂO  //////////////////////////////////

	$posicao = $_POST['posicao'];

////////// IMAGEM //////////////////////////////////
	$arquivo_tmp_Imagem = $_FILES['imagem']['tmp_name'];
	$nome_Imagem = $_FILES['imagem']['name'];

	// Pega a extensao
	$extensao_Imagem = strrchr($nome_Imagem, '.');

	// Converte a extensao para mimusculo
	$extensao_Imagem = strtolower($extensao_Imagem);
	$nome_Imagem = $nome . $extensao_Imagem;

	// Concatena a pasta com o nome do arquivo
    $destino_Imagem = 'assets/Elementos/Etapa_1/' . $nome_Imagem;

//////////// AUDIO /////////////////////////////////

	$arquivo_tmp_Audio = $_FILES['audio']['tmp_name'];
    $nome_Audio = $_FILES['audio']['name'];

    // Pega a extensao
    $extensao_Audio = strrchr($nome_Audio, '.');

    // Converte a extensao para mimusculo
    $extensao_Audio = strtolower($extensao_Audio);
     $nome_Audio = $nome . $extensao_Audio;

     // Concatena a pasta com o nome do arquivo
     $destino_Audio = 'assets/Sons/Elementos/' . $nome_Audio;

//////////// PROCEDIMENTOS ///////////////////////////////////////////

    // tenta mover o arquivo para o destino
    if( @move_uploaded_file( $arquivo_tmp_Imagem, $destino_Imagem  )  && @move_uploaded_file( $arquivo_tmp_Audio, $destino_Audio  ) )
    {
     
		
			
         
        // <strong>" . $_FILES['arquivo']['name'] . "</strong>

    /////////////// ESTABELECENDO CONEXÃO COM O BANCO DE DADOS ////////////////////////
        // Estabelecendo conexão com o Banco de Dados
        require("conectar.php");//chama o arquivo de conexão ao BD

        //salvando no Banco de Dados, inserindo os dados na Tabela "dados" através de comandos MySQL.*/

       $sqlinsert = "INSERT INTO elementos (nome)VALUES('$nome')"; 
		
	


        //verifica a ocorrência de erro
        mysql_query("UPDATE elementos SET nome='$nome' WHERE id='$posicao' ") or die("Não foi possível inserir os dados");
					
		
		header ("location: cadastro.html");
		//echo "Elemento: Inserido com sucesso!";
		
    }

    else
        echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";

}
else{
	
	
	header ("location: cadastro.html");

}
?>
</body>
</html>