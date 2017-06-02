<?php
$connect = mysql_connect("localhost","user","pass");
mysql_set_charset('utf8',$connect);
$db = mysql_select_db("valedasaguas",$connect) or die ("Não foi possível selecionar o Banco de dados.");

// Recuperamos a ação enviada pelo formulário
$a = $_GET['a'];

// Verificamos se a ação é de busca
if ($a == "buscar") {

	// Pegamos a palavra
	$nome = trim($_POST['pesquisa']);
	$status = trim($_POST['status']);
	$data = $_POST['data'];
	$destino = $_POST['destino'];
	

	// Verificamos no banco de dados contatos equivalente a palavra digitada
	$sql = mysql_query("SELECT * FROM vendas WHERE nome LIKE '%".$nome."%' AND status LIKE '%".$status."%' AND month(data) LIKE '%".$data."%' AND destino LIKE '%".$destino."%' ORDER BY nome");

	// Descobrimos o total de registros encontrados
	$numRegistros = mysql_num_rows($sql);
	
	echo "<table class='table table-bordered table-hover' align ='center' display: block>";
	echo "<thead  align='center'>";
	echo "<tr class='row bg-primary'>";
		
	echo "<th colspan='2'>NOME</th>";
	echo "<th>TIPO</th>";
	echo "<th>QUANTIDADE</th>";
	echo "<th>STATUS</th>";
	echo "<th>DESTINO</th>";
	echo "<th>DATA</th>";
	echo "<th >OBSERVAÇÃO</th>";	
	echo "</tr>";	
	echo "</thead>";		

	// Se houver pelo menos um registro, exibe-o
	if ($numRegistros != 0) {
		
		while ($contatos = mysql_fetch_object($sql)) {
	
			echo "<tr class='row' align = 'center'>";
				echo "<td colspan='2'><a href='editar_contato.php?id=". $contatos->nome ."'>" . $contatos->nome . "</td>";
				echo "<td>" . $contatos->tipo . "</td>";
				echo "<td>" . $contatos->quantidade . "</td>";
				echo "<td>" . $contatos->status .  "</td>";
				echo "<td>" . $contatos->destino . "</td>";
				echo "<td>" . date('d/m/Y', strtotime($contatos->data)) . "</td>";
				echo "<td>" . $contatos->observacao . "</td>";
			echo "</tr>";
			echo "<a href='#' id='footer'></a>";
			
			if ($contatos->tipo == "AGUA"){
				$totalagua += $contatos->quantidade * 3;
			}
			if ($contatos->tipo == "AGUA E GARAFAO"){
				$totalaguagarafao += $contatos->quantidade * 12;
			}
			if ($contatos->tipo == "GARRAFAO"){
				$totalagarafao += $contatos->quantidade * 9;
			}
			if ($contatos->tipo == "CAMINHAOPIPA"){
				$totalcaminhao += $contatos->quantidade * 240;
			}
			
			if ($contatos->status == "PAGO" && $contatos->tipo == "AGUA"){
				$statuspagoagua += $contatos->quantidade * 3;
			}elseif ($contatos->status == "DEVENDO" && $contatos->tipo == "AGUA") {
				$statusdevedoragua += $contatos->quantidade * 3;
			}
			
			if ($contatos->status == "PAGO" && $contatos->tipo == "AGUA E GARAFAO"){
				$statuspagoag += $contatos->quantidade * 12;
			}elseif ($contatos->status == "DEVENDO" && $contatos->tipo == "AGUA E GARAFAO"){
				$statusdevedorag += $contatos->quantidade * 12;
			}
			
			if ($contatos->status == "PAGO" && $contatos->tipo == "GARRAFAO"){
				$statuspagog += $contatos->quantidade * 9;
			}elseif ($contatos->status == "DEVENDO" && $contatos->tipo == "GARRAFAO") {
				$statusdevedorg += $contatos->quantidade * 9;
			}
			
			if ($contatos->status == "PAGO" && $contatos->tipo == "CAMINHAOPIPA"){
				$statuspagocp += $contatos->quantidade * 240;
			}elseif ($contatos->status == "DEVENDO" && $contatos->tipo == "CAMINHAOPIPA"){
				$statusdevedorcp += $contatos->quantidade * 240;
			}
			
		}
		echo "<tr class='row' align='center'>";
			echo "<td colspan='8'></td>";
		echo "</tr>";
		echo "<tr class='row' align='center'>";
		
				echo "<td colspan='2'>Total das Vendas apenas Agua R$: " . $statuspagoagua . "</td>";
				echo "<td colspan='2'>Total das Vendas Agua + Garafão R$: " . $totalaguagarafao . "</td>";
				echo "<td colspan='2'>Total das Vendas apenas Garafão R$: " . $totalagarafao . "</td>";
				echo "<td colspan='2'>Total das Vendas Caminhão Pipa R$: " . $totalcaminhao . "</td>";
				echo "</tr>";	
		
		echo "<tr class='row' align='center'>";
				echo "<td colspan='1'>PAGAS R$: " . $totalagua . "</td>";
				echo "<td colspan='1'>A PAGAR R$: " . $statusdevedoragua . "</td>";
				
				echo "<td colspan='1'>PAGOS R$: " . $totalaguagarafao . "</td>";
				echo "<td colspan='1'>A PAGAR R$: " . $statusdevedorag . "</td>";
				
				echo "<td colspan='1'>PAGOS  R$: " . $statuspagog . "</td>";
				echo "<td colspan='1'>A PAGAR R$: " . $statusdevedorg . "</td>";
				
				echo "<td colspan='1'>PAGOS R$: " . $statuspagocp . "</td>";
				echo "<td colspan='1'>A PAGAR R$: " . $statusdevedorcp . "</td>";
		echo "</tr>";		
				
				
		echo "</table>";
		echo "<a href='#' id='footer'></a>";
		
	// Se não houver registros
	} else {
		echo "<a href='#' id='footer'></a>";
		echo "<center>Nenhuma venda foi encontrado! tente novamente.</center>";
	}
}
?>