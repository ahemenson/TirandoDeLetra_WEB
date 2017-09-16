<?php
$local_serve = "localhost";
$usuario_serve = "root";
$senha_serve = "";
$banco_de_dados = "jogotirandodeletra";
$conexao=@mysql_connect($local_serve,$usuario_serve,$senha_serve) or die ("O servidor não responde!");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
$db = @mysql_select_db($banco_de_dados,$conexao)
or die ("Não foi possivel conectar-se ao banco de dados!");
?>  