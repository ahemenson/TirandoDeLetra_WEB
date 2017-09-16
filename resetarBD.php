<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload de arquivos</title>
</head>

<body>
<?php

		$contador = 1; 
		
    /////////////// ESTABELECENDO CONEXÃO COM O BANCO DE DADOS ////////////////////////
        // Estabelecendo conexão com o Banco de Dados
        require("conectar.php");//chama o arquivo de conexão ao BD

        //salvando no Banco de Dados, inserindo os dados na Tabela "dados" através de comandos MySQL.*/

        $sqlselect = "SELECT * FROM elementospadrao";

	    // Executa a query (o recordset $rs contém o resultado da query)
	    $resultado = mysql_query($sqlselect);
		
	    // Loop pelo recordset $rs
	    // Cada linha vai para um array ($row) usando mysql_fetch_array
	    while($row = mysql_fetch_array($resultado)) {

	        // Escreve o valor da coluna Nome (que está no array $row)
	        //echo $row['nome'] ."<br />";
			$msg =  $row['nome'];
				
			mysql_query("UPDATE elementos SET nome='$msg' WHERE id='$contador' ") or die(mysql_error());
 		
			//echo $contador . " " .$row['nome'];
			
			$contador = $contador + 1;			
	    }
		
		echo "Elementos: Resetados com sucesso!";		
		header ("location: cadastro.html");
		
		
	
?>
</body>
</html>