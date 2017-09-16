<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="iso-8859-1" />
    <title>Tirando de Letra</title>
	<script type="text/javascript" src="js/phaser.min.js"></script>
    <link rel="stylesheet" type="text/css" href="_css/estiloJogo.css">
    </head>
<body>

<?php

	$contador = 0; 
	$meu_array = array();

    /////////////// ESTABELECENDO CONEXÃO COM O BANCO DE DADOS ////////////////////////

    // Estabelecendo conexão com o Banco de Dados
    require("conectar.php");//chama o arquivo de conexão ao BD

    /////////// SELECIONANDO CAMPOS DO BANCO DE DADOS //////////////////////////

    $sqlselect = "SELECT * FROM elementos";

    // Executa a query (o recordset $rs contém o resultado da query)
    $resultado = mysql_query($sqlselect);
	
    // Loop pelo recordset $rs
    // Cada linha vai para um array ($row) usando mysql_fetch_array
    while($row = mysql_fetch_array($resultado)) {

        // Escreve o valor da coluna Nome (que está no array $row)
        //echo $row['nome'] ."<br />";
		//$msg =  $row['nome'];
		
		$meu_array[$contador] = $row['nome'];
		
		$contador = $contador + 1;		
    }
						
?>


<script type="text/javascript">


var game = new Phaser.Game(1000, 650, Phaser.AUTO, '', { preload: preload, create: create, update: update });
var score = 0;
var scoreText;
var elemento; //
var contador = 0; // responsavel por incrementar e mudar as imagens dos elementos

var letras = []; // conjunto de letras
var palavras = []; // conjundto de palavras reservadas
var imagemElementos = []; // conjunto de imagens dos elementos
var imagemLetras = [];

var teste;
var espacos = []; //vetores para configurar a quantidade de espacos

var posElementoX = 700;
var posElementoY = 180;

pos_X_letraOriginal = 60; //guarda a posicao de origem das letras
var pos_X_letra = pos_X_letraOriginal;
var pos_Y_letra = 150;

pos_X_espacoOriginal = 250; //guarda a posicao de origem dos espacos
var pos_X_espaco = pos_X_espacoOriginal;
var pos_Y_espaco = 550;

var palavraFormada = []; // variavel reservada para guardar as letras arrastadas para os espacos, formando assim a palavra

var botaoValidar; // botao acionado para verificar se a palavra montada esta de acordo com o nome do elemento
var botaoAudio;
var SomDoElemento;
var musicaDoJogo;
var botaoMusica;
var SomDeErro;
var SomAcerto;

var botaoFechar;
var telaMesnagemPopup;
var tween = null;
var pw = 0;
var ph = 0;

var mensagem; // variavem que gurdara o texto da mensagem
var style;
var imagemNiveis; // vetor com os numeros dos niveis

function preload() {

    // carregando os elementos do cenario
    game.load.image('fundo', 'assets/fundos/fundo4.jpg');
    game.load.image('quadro', 'assets/fundos/quadro.png');
    game.load.image('madeira', 'assets/fundos/madeira.png');
    game.load.image('espaco', 'assets/letras/espaco.png');
    game.load.image('botaoValidar', 'assets/Botoes/validar.png');
    game.load.image('botaoAudio', 'assets/Botoes/audio.png');
    game.load.spritesheet('botaoMusica', 'assets/Botoes/som_spritesheet.png', 0, 115);
    game.load.image('barraSuperior', 'assets/Botoes/barra_niveis2.png');

    // carregando os elementos da tela de popup
    game.load.image('fundoPopup', 'assets/fundos/tabela_ii.png');
    game.load.image('botaoFechar', 'assets/Botoes/sair.png');

    // carregando os sons
    game.load.audio('musicaDoJogo', ['assets/Sons/music_game.mp3', 'assets/Sons/music_game.ogg']);
    game.load.audio('somErro', 'assets/Sons/erro.mp3');
    game.load.audio('SomAcerto','assets/Sons/audio_acerto.mp3');

    // * elemento vazio
    game.load.image('elemento', 'assets/Elementos/Etapa_1/vazio.png');

    // carregando as palavras
   //palavras = ['gato','bala','copo','dado','fogo','pato','rato','urso','gelo','coruja', 'boneca','caneta','espada',
    //         'cachorro','borboleta','papagaio','biclicleta','telefone','computador','chocolate'];
	
	palavras[0] = "<?php echo $meu_array[0]?>";palavras[1] = "<?php echo $meu_array[1]?>";palavras[2] = "<?php echo $meu_array[2]?>";
	palavras[3] = "<?php echo $meu_array[3]?>";palavras[4] = "<?php echo $meu_array[4]?>";palavras[5] = "<?php echo $meu_array[5]?>";
	palavras[6] = "<?php echo $meu_array[6]?>";palavras[7] = "<?php echo $meu_array[7]?>";palavras[8] = "<?php echo $meu_array[8]?>";
	palavras[9] = "<?php echo $meu_array[9]?>";palavras[10] = "<?php echo $meu_array[10]?>";palavras[11] = "<?php echo $meu_array[11]?>";
	palavras[12] = "<?php echo $meu_array[12]?>";palavras[13] = "<?php echo $meu_array[13]?>";palavras[14] = "<?php echo $meu_array[14]?>";
	palavras[15] = "<?php echo $meu_array[15]?>";palavras[16] = "<?php echo $meu_array[16]?>";palavras[17] = "<?php echo $meu_array[17]?>";
	palavras[18] = "<?php echo $meu_array[18]?>";palavras[19] = "<?php echo $meu_array[19]?>";

    imagemNiveis = new Array(palavras.length); // configura o tamanho do vetor de acordo com a quantidade de palavras

    // carregando os vetores dos proximos elementos
    for (var i = 0; i < palavras.length; i++){
        imagemElementos[i] = game.load.image(palavras[i], 'assets/Elementos/Etapa_1/'+palavras[i]+'.png');
        game.load.audio(palavras[i], [ 'assets/Sons/Elementos/'+palavras[i]+'.mp3', 'assets/Sons/Elementos/'+palavras[i]+'.ogg']);
        game.load.image((i+1)+'', 'assets/Botoes/'+(i+1)+'.png');
    }

     //carregando as letras
    letras = ['a','á','â','ã','b','c','d','e','é','ê','f','g','h','i','í','j','l','m','n','o','ó','ô','õ','p','q','r','s','t','u','ú','v','x','z'];

    for (var i = 0; i < letras.length; i++) {
      game.load.image(letras[i], 'assets/Letras/'+letras[i]+'.png');
     }

}

// ====================================================================================================================
//************************************** Metodo create **********************************************************
function create() {

    //  We're going to be using physics, so enable the Arcade Physics system
    this.game.physics.startSystem(Phaser.Physics.ARCADE);

    //  aplicando as imagens que compoem o fundo do cenario (posicaox, posicaoy, id)
    game.add.sprite(0, 50, 'fundo').scale.setTo(1.7,1.7);
    game.add.sprite(10,60, 'quadro').scale.setTo(1.7,1.7);
    game.add.sprite(-5,532, 'madeira').scale.setTo(1.6,1.2);
    game.add.sprite(0, 0, 'barraSuperior').scale.setTo(1.2,1.2);

    elemento = game.add.sprite(posElementoX, posElementoY,'elemento'); // *elemento representa a imagem do objeto que sera exposto na tela
    elemento.scale.setTo(0.9,0.9);

    carregaImagemNiveis(); // metodo responsavel por adicionar na tela todos os numeros que representam os niveis do jogo
    novoDesafio(); // metodo responsavel por fazer a primeira configuracao adicionando letras, elementos e etc na tela
    addBotoes(); // metodo responsavel por adicionar botoes na tela
    configuraTelaMensagem();
    addSons();
    tocarMusica();

    //  Our controls.
    cursors = game.input.keyboard.createCursorKeys();
}
//====================================================================================================================
//************************************** METODO UPDATE **********************************************************

function update() {
    render();
}

//====================================================================================================================
//************************************** METODOS - EFEITOS SONOROS **********************************************************

function tocarSomElemento(){
      SomDoElemento = game.add.audio(palavras[contador]); // atualiza a variavel carregando o som da palavra atual
      SomDoElemento.play();
}

function tocarMusica(){
    botaoMusica.frame = 0;
    musicaDoJogo.play();
}

function pausaMusica(){
    botaoMusica.frame = 1;
    musicaDoJogo.pause();
}

function tocarSomErro() {
    SomDeErro.play();
}

function tocarDeAcerto() {
    SomAcerto.play();
}

function estadoMusica(){
    if(musicaDoJogo.isPlaying){
        pausaMusica();
    }
    else{
        tocarMusica();
    }
}

function addSons(){
    musicaDoJogo = game.add.audio('musicaDoJogo');
    musicaDoJogo.loop = true;
    musicaDoJogo.volume = 0.2;

    SomDeErro = game.add.audio('somErro');
    SomDeErro.volume = 0.5;

    SomAcerto = game.add.audio('SomAcerto');
    SomAcerto.volume = 0.3;
}

//====================================================================================================================
//************************************** METODOS - EFEITOS GRAFICOS **********************************************************

function carregaImagemNiveis(){ //metodo responsavel por adicionar um conjunto de imagens que representam os niveis do jogo

    var x = 100;//(game.world.centerX/2)+10;
    //windo.alert(x);
    for (var i = 0; i <palavras.length; i++){
        imagemNiveis[i] = game.add.sprite(x , 15, (i+1)+'');
        imagemNiveis[i].scale.setTo(0.2,0.2);
        x = x + 40;
    }
}

function abreTelaMensagem() {

    telaMesnagemPopup.alpha = 1.0; // torna a tela visivel

    if ((tween !== null && tween.isRunning) || telaMesnagemPopup.scale.x === 1)
    {
        return;
    }
    //  Create a tween that will pop-open the window, but only if it's not already tweening or open
    tween = game.add.tween(telaMesnagemPopup.scale).to( { x: 1, y: 1 }, 2000, Phaser.Easing.Elastic.Out, true);

    mensagem.alpha = 1.0; // torna o texto da mensagem visivel

}

function fechaTelaMensagem() {

    if (tween && tween.isRunning || telaMesnagemPopup.scale.x === 0.1)
    {
        return;
    }
    //  Create a tween that will close the window, but only if it's not already tweening or closed
    tween = game.add.tween(telaMesnagemPopup.scale).to( { x: 0.1, y: 0.1 }, 500, Phaser.Easing.Elastic.In, true); // responsavel pela animação da tela de mensagem
    mensagem.alpha = 0.0; // torna a o texto da mensaem invisivel
    telaMesnagemPopup.alpha = 0.0; // torna a tela invisivel
}

function efeitoNivel(){ // metodo responsavel por aplicar os efeitos de transição para os numeros

    if(contador>palavras.length){
        //no faz nada,
    }
    if(contador==0){ // caso o jogo tenha iniciado
        imagemNiveis[contador].scale.set(0.3 , 0.3);
        imagemNiveis[contador].position.y = 10; // sobe um pouco a posico original
        imagemNiveis[contador].position.x = (imagemNiveis[contador].position.x - 5); // desloca um pouco a posicao original
        imagemNiveis[contador].tint = 0xa7fff0;

    }
    else{
        // nivel atual
        imagemNiveis[contador].scale.set(0.3 , 0.3);
        imagemNiveis[contador].position.y = 10; // sobe um pouco a posico original
        imagemNiveis[contador].position.x = (imagemNiveis[contador].position.x - 5); // desloca um pouco a posicao original
        imagemNiveis[contador].tint = 0xa7fff0;
        // nivel anterior
        imagemNiveis[contador-1].scale.set(0.2 , 0.2);
        imagemNiveis[contador-1].position.y = 15; // retorn a  posico original
        imagemNiveis[contador-1].position.x = (imagemNiveis[contador-1].position.x + 5); // retorna a posicao original
        imagemNiveis[contador-1].tint = 0xFFFFFF;
    }

}

function configuraTelaMensagem(){

    telaMesnagemPopup = game.add.sprite(game.world.centerX, game.world.centerY, 'fundoPopup'); // tela exibida com uma mensagem
    telaMesnagemPopup.alpha = 0.0; // habilita a invisibilidade
    telaMesnagemPopup.anchor.set(0.5, 0.4);
    telaMesnagemPopup.inputEnabled = true;

    //  Position the close button to the top-right of the popup sprite (minus 8px for spacing)
    pw = (telaMesnagemPopup.width / 2) - 30;
    ph = (telaMesnagemPopup.height / 2) - 8;

    //  And click the close button to close it down again
    botaoFechar = game.make.sprite(pw, -ph, 'botaoFechar');
    botaoFechar.scale.setTo(0.5,0.5);
    botaoFechar.inputEnabled = true;
    botaoFechar.input.priorityID = 1;
    botaoFechar.input.useHandCursor = true;
    botaoFechar.events.onInputDown.add(fechaTelaMensagem, this);

    //  Add the "close button" to the popup window image
    telaMesnagemPopup.addChild(botaoFechar);

    //  Hide it awaiting a click
    telaMesnagemPopup.scale.set(0.1);

    style = { font: "30px Arial", fill: "#ffab0c", wordWrapWidth: telaMesnagemPopup.width, fontWeight: "bold",
        stroke: "#ffffff", strokeThickness: 5}; // configura o estilo da mensagem

    // adicionando mensagem na tela
    mensagem = game.add.text(0, 0, "Ops, amiguinho!\n Tente novamente", style);
    mensagem.anchor.set(0.6, 0.4);
    mensagem.alpha = 0.0; // torna invisivel
    mensagem.x = Math.floor(telaMesnagemPopup.x + telaMesnagemPopup.width / 2);
    mensagem.y = Math.floor(telaMesnagemPopup.y + telaMesnagemPopup.height / 2);

}

function render() {
    //game.debug.text(imagemLetras[0].position + ' --- '+ espacos[1].position, 10, 20);
    // game.debug.text(palavraFormada, 10, 20);
}

//====================================================================================================================
//**************** METODOS - CONTROLE E CONFIGURACAO DE DESAFIOS E ELEMENTOS EXISTENTES NA TELA********************

function novoDesafio(){ // metodo chamado quando o jogo se inicia pela primeira vez

    novoElemento();
    carregaEspaco();
    carregaLetras(); //metodo responsavel por carregar somente as letras da palavra
    configuraPalavraFormada(); //metodo responsavel por configurar o tamanho correspondente com a palavra do elemento em questao
    efeitoNivel(); // aplica o efeito de destaque do nivel
}

function novoElemento() {

    if(contador>=palavras.length){ // tela de fim de jogo
        window.location = "telaFim.html";
    }
    else{
        elemento.destroy();  //destroi o elemento
        elemento = game.add.sprite(posElementoX, posElementoY, palavras[contador]);  //insere uma nova imagem para o novo elemento
        elemento.scale.setTo(0.7, 0.7);  //redimensiona a imagem do elemento
    }
}

function carregaEspaco() { // metodo para configurar a quantidade exata de espacos brancos na tela (em tempo de execucao)

    limpaEspacosTela();
    espacos = new Array(palavras[contador].length);  // configura a quantidade de posicoes do vetor de acordo com a palavra no momento(contador)

    if(espacos.length <= 4){
        pos_X_espaco = pos_X_espacoOriginal; // sempre carrega o espaco a partir na posicao inicial
    }
    else if(espacos.length <= 6){
        pos_X_espaco = (pos_X_espacoOriginal - 100);
    }
    else if(espacos.length >=8){
        pos_X_espaco = (pos_X_espacoOriginal - 230);
    }

    for (var i = 0; i < espacos.length; i++) {  // percorre setando as  posicoes dos espacos na tela
        espacos[i] = game.add.sprite(pos_X_espaco, pos_Y_espaco, 'espaco');
        espacos[i].scale.setTo(0.4, 0.4);
        pos_X_espaco = pos_X_espaco + 100;
    }
}

function carregaLetras() {

    limpaImagemLetrasTela(); // limpa as letras existentes na tela para nao acumular

    imagemLetras = new Array(palavras[contador].length); // configura o tamanho do vetor para o mesmo tamanho da palavra

    pos_X_letra = pos_X_letraOriginal; // sempre que for adicionadas imagensLetras serao iniciadas a partir desse posicao inicial

    for (var i = 0; i < letras.length; i++) {
        for (var j = 0; j < palavras[contador].length; j++) {
            if (letras[i].toString() == palavras[contador].charAt(j).toString()) {
                imagemLetras[j] = game.add.sprite(pos_X_letra, pos_Y_letra, letras[i]);
                imagemLetras[j].scale.setTo(0.4, 0.4);
                imagemLetras[j].inputEnabled = true;
                imagemLetras[j].input.useHandCursor = true;
                imagemLetras[j].input.enableDrag(true);
                //imagemLetras[j].input.enableSnap(20, 20, false, true);
                imagemLetras[j].originalPosition = imagemLetras[j].position.clone();
                imagemLetras[j].events.onDragStop.add(stopDrag, {param1: imagemLetras[j]});
                pos_X_letra = pos_X_letra + 90; //incrementa a posicao em x
            }
        }
    }
}

function configuraPalavraFormada(){  // metodo responsavel por configurar o tamnaho do vetor da palavra formada de acordo com o nome do elemento

    palavraFormada = new Array(palavras[contador].length);

    for (var i = 0; i < palavraFormada.length; i++) {//percorre o vetor atribuindo valores n�o nulos
        palavraFormada[i]='*';
    }
}

function limpaEspacosTela(){
    for (var i = 0; i <espacos.length; i++) {
        espacos[i].destroy();
    }
}

function incrementaControladorDeElementos() { //metodo responsavel por incrementar as posicoes para poder acessar os elementos
    contador = contador + 1;  //incrementa o contador
}

function limpaImagemLetrasTela(){
    for (var i = 0; i <imagemLetras.length; i++) {
        imagemLetras[i].destroy();
    }
}

function addBotoes(){
    botaoValidar = game.add.sprite(800, 545,'botaoValidar');
    botaoValidar.scale.setTo(0.8,0.8);
    botaoValidar.inputEnabled = true;
    botaoValidar.input.useHandCursor = true;
    botaoValidar.events.onInputDown.add(verificaPalavra);

    botaoAudio = game.add.sprite(900, 100,'botaoAudio');
    botaoAudio.scale.setTo(0.8,0.8);
    botaoAudio.inputEnabled = true;
    botaoAudio.input.useHandCursor = true;
    botaoAudio.events.onInputDown.add(tocarSomElemento);

    botaoMusica = game.add.sprite(950, 2, 'botaoMusica');
    botaoMusica.scale.setTo(0.4,0.4);
    botaoMusica.inputEnabled = true;
    botaoMusica.input.useHandCursor = true;
    botaoMusica.events.onInputDown.add(estadoMusica);
}

function stopDrag(param1){ // metodo responsavel por verfificar se a imagem de uma letra esta em cima de um espaco branco

    for (var j = 0; j <espacos.length; j++) {  //varre todos os espacos existentes na tela
        if (Phaser.Rectangle.intersects(param1.getBounds(), espacos[j].getBounds())) { // se ha intersecaoo entre letra e espaco

            param1.position.copyFrom(espacos[j].position);//coloca a letra na posicao do espaco
            break;
        }
        else {
            param1.position.copyFrom(param1.originalPosition); //coloca a letra na posicao original
        }
    }

}

function atualizaPalavraFormada(){ // metodo responsavel por verfificar se a imagem de uma letra esta em cima de um espaco branco

     for (var i = 0; i < imagemLetras.length; i++) { //varre todas as letras existente na tela
        for (var j = 0; j <espacos.length; j++) {  //varre todos os espacos existentes na tela
            if (Phaser.Rectangle.intersects(imagemLetras[i].getBounds(), espacos[j].getBounds())) { // se ha intersecaoo entre letra e espaco

                imagemLetras[i].position.copyFrom(espacos[j].position);//coloca a letra na posicao do espaco
                palavraFormada[j]=imagemLetras[i].key; //coloca a letra na posicao equivalente em que esta no espaco
                break;
            }
            else {
                imagemLetras[i].position.copyFrom(imagemLetras[i].originalPosition); //coloca a letra na posicao original

            }
        }
    }
}

function verificaPalavra(){

    configuraPalavraFormada();
    atualizaPalavraFormada();
    var cont=0;




    for (var i = 0; i < palavraFormada.length; i++) {//varre todas as letras existente na tela
        if(palavraFormada[i].toString() == palavras[contador].charAt(i).toString()){ //
            cont = cont + 1;

             window.alert("letra da palavra formada: "+ palavraFormada[i].toString() + " | " + "letra da palavra do desafio: " +  palavras[contador].charAt(i).toString(), 100, 100);

        }
        else{
            imagemLetras[i].position.copyFrom(imagemLetras[i].originalPosition);
            abreTelaMensagem();
            tocarSomErro();
        }
    }

    if(cont==palavraFormada.length){ // se todas as letras estão na posicao correta
        incrementaControladorDeElementos();
        tocarDeAcerto();
        novoDesafio();
    }
}

</script>

</body>
</html>
