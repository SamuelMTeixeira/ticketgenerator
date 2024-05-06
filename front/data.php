<?php
$SelTicket = "SELECT * FROM glpi_tickets WHERE id = '" . $_GET['id'] . "'";
$ResTicket = $DB->query($SelTicket);
$Ticket = $DB->fetchAssoc($ResTicket);
$OsId = $_GET['id'];
$OsNome = $Ticket['name'];
$SelDataOs = "SELECT date,date_format(date, '%d/%m/%Y') AS DataOs FROM glpi_tickets WHERE id = '" . $_GET['id'] . "'";
$ResSelData = $DB->query($SelDataOs);
$ResSelDataFinal = $DB->fetchAssoc($ResSelData);
$DataOs = $ResSelDataFinal['DataOs'];
$SelDataInicial = "SELECT date,date_format(date, '%d/%m/%Y %H:%i') AS DataInicio FROM glpi_tickets WHERE id = '" . $_GET['id'] . "'";
$ResDataInicial = $DB->query($SelDataInicial);
$DataInicial = $DB->fetchAssoc($ResDataInicial);
$OsData = $DataInicial['DataInicio'];
$OsDescricao = $Ticket['content'];
$SelDataFinal = "SELECT time_to_resolve,date_format(solvedate, '%d/%m/%Y %H:%i') AS DataFim FROM glpi_tickets WHERE id = '" . $_GET['id'] . "'";
$ResDataFinal = $DB->query($SelDataFinal);
$DataFinal = $DB->fetchAssoc($ResDataFinal);
$OsDataEntrega = $DataFinal['DataFim'];
$SelSolucaoTicket = "SELECT * FROM glpi_itilsolutions WHERE items_id = '" . $_GET['id'] . "' AND (status = '2' OR status = '3')";
$ResSolucaoTicket = $DB->query($SelSolucaoTicket);
$SolucaoTicket = $DB->fetchAssoc($ResSolucaoTicket);
$OsSolucao = is_null($SolucaoTicket)  ? 0 : $SolucaoTicket['content'];
$SelTicketUsers = "SELECT * FROM glpi_tickets_users WHERE tickets_id = '" . $OsId . "'";
$ResTicketUsers = $DB->query($SelTicketUsers);
$TicketUsers = $DB->fetchAssoc($ResTicketUsers);


$OsResponsavel = "";
$OsUserId = $TicketUsers['users_id'];
$SelIdOsResponsavel = "SELECT users_id FROM glpi_tickets_users WHERE tickets_id = '".$OsId."' AND type = 2";
$ResIdOsResponsavel = $DB->query($SelIdOsResponsavel);

while ($IdOsResponsavel = $DB->fetchAssoc($ResIdOsResponsavel)) {
	$SelOsResponsavelName = "SELECT * FROM glpi_users WHERE id = '".$IdOsResponsavel['users_id']."'";
	$ResOsResponsavelName = $DB->query($SelOsResponsavelName);
	$OsResponsavelFull = $DB->fetchAssoc($ResOsResponsavelName);
	$OsResponsavel .= $OsResponsavelFull['firstname']. " " .$OsResponsavelFull['realname']. ", ";
}
if(strlen($OsResponsavel)>2){
	$OsResponsavel = substr($OsResponsavel, 0, strlen($OsResponsavel)-2);
}

$SelAtendimento = "select max(date_format(date_mod, '%d/%m/%Y %H:%i')) as date_mod from glpi_logs where itemtype like 'Ticket' and id_search_option=12 and new_value=15 and items_id=" . $OsId;
$ResDtAtendimento = $DB->query($SelAtendimento);
if ($ResDtAtendimento) {
  $dtatend = $DB->fetchAssoc($ResDtAtendimento);
  if ($dtatend) {
    $OsDataAtendimento = $dtatend['date_mod'];
  }
}
$EntidadeId = $Ticket['entities_id'];
$SelEmpresa = "SELECT * FROM glpi_entities WHERE id = '" . $EntidadeId . "'";
$ResEmpresa = $DB->query($SelEmpresa);
$Empresa = $DB->fetchAssoc($ResEmpresa);
$EntidadeName = $Empresa['name'];
$EntidadeCep = $Empresa['postcode'];
$EntidadeEndereco = $Empresa['address'];
$EntidadeEmail = $Empresa['email'];
$EntidadePhone = $Empresa['phonenumber'];

$SelEmail = "SELECT * FROM glpi_useremails WHERE users_id = '" . $OsUserId . "'";
$ResEmail = $DB->query($SelEmail);
$Email = $DB->fetchAssoc($ResEmail);
$UserEmail = $Email['email'] ?? "";

$SelCustoLista = "SELECT actiontime, sec_to_time(actiontime) AS Hora,name,cost_time,cost_fixed,cost_material,FORMAT(cost_time,2,'de_DE') AS cost_time2, FORMAT(cost_fixed,2,'de_DE') AS cost_fixed2, FORMAT(cost_material,2,'de_DE') AS cost_material2, SUM(cost_material + cost_fixed + cost_time * actiontime/3600) AS CustoItem FROM glpi_ticketcosts WHERE tickets_id = '" . $OsId . "' GROUP BY id";
$ResCustoLista = $DB->query($SelCustoLista);
$SelCusto = "SELECT SUM(cost_material + cost_fixed + cost_time * actiontime/3600) AS SomaTudo FROM glpi_ticketcosts WHERE tickets_id = '" . $OsId . "'";
$ResCusto = $DB->query($SelCusto);
$Custo = $DB->fetchAssoc($ResCusto);
$CustoTotal =  $Custo['SomaTudo'] ?? 0;
$CustoTotalFinal = number_format($CustoTotal, 2, ',', ' ');
$SelTempoTotal = "SELECT SUM(actiontime) AS TempoTotal FROM glpi_ticketcosts WHERE tickets_id = '" . $OsId . "'";
$ResTempoTotal = $DB->query($SelTempoTotal);
$TempoTotal = $DB->fetchAssoc($ResTempoTotal);
$seconds = $TempoTotal['TempoTotal'];
$hours = floor($seconds / 3600);
$seconds -= $hours * 3600;
$minutes = floor($seconds / 60);
$seconds -= $minutes * 60;
$SelLocId = "SELECT locations_id FROM `glpi_tickets` WHERE id = '" . $OsId . "'";
$ResLocId = $DB->query($SelLocId);
$LocId = $DB->fetchAssoc($ResLocId);
$LocationsId = $LocId['locations_id'];
$SelNameLoc = "SELECT name, completename FROM glpi_locations WHERE id = '" . $LocationsId . "'";
$ResNameLoc = $DB->query($SelNameLoc);
$Loc = $DB->fetchAssoc($ResNameLoc);
$Locations = $Loc['completename']  ?? "";
$SelTicketUsers = "SELECT * FROM glpi_tickets_users WHERE tickets_id = '" . $OsId . "'";
$ResTicketUsers = $DB->query($SelTicketUsers);
$TicketUsers = $DB->fetchAssoc($ResTicketUsers);
$OsUserId = $TicketUsers['users_id'];
$SelUsers = "SELECT * FROM glpi_users WHERE id = '" . $OsUserId . "'";
$ResUsers = $DB->query($SelUsers);
$Users = $DB->fetchAssoc($ResUsers);
$UserName = $Users['firstname'] . " " . $Users['realname'];
$UserCpf = $Users['registration_number'];
$UserTelefone = $Users['mobile'];
$UserEndereco = $Users['comment'];
$UserCep = $Users['phone2'];
$Phone = $Users['mobile'];
$SelEmail = "SELECT * FROM glpi_useremails WHERE users_id = '" . $OsUserId . "'";
$ResEmail = $DB->query($SelEmail);
$Email = $DB->fetchAssoc($ResEmail);
$UserEmail = $Email['email'] ?? "";