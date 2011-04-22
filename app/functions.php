<?php

function windowClose($refreshOpenerWindow=false, $submitButtonId="submit") {
?>
<script language="javascript">
  <?if($refreshOpenerWindow):?>
    //window.opener.location.href = window.opener.location.href;
    //Busca a página novamente no servidor
    //window.opener.location.reload(true);
    //Não busca a página novamente no servidor
    //window.opener.location.reload(false);
    //window.opener.location.refresh();
    window.opener.document.getElementById("<?=$submitButtonId?>").click();
    if(window.opener.progressWindow)
       window.opener.progressWindow.close();
  <?endif;?>
  window.close();
</script>
<?
}


function createComboBox($itens, $keyFieldName, $descriptionField, $name="", $selectedValue="0", 
 $enabled=true, $includeEmptyItem=true, $emptyItemText="", $events="")
{
  if($name=="")
     $name = $keyFieldName;

  $enabledProp = "";
  if(!$enabled) 
     $enabledProp = "disabled=\"disabled\" style=\"background-color: silver;\" ";
?>
  <select name="<?=$name?>" id="<?=$name?>" <?=$events?> <?=$enabledProp?> >
  <?
  if(count($itens) == 0)
    echo "<option value='0'>Nenhum item encontrado</option>";
  elseif($includeEmptyItem)
    echo "<option value='0'>$emptyItemText</option>";
  foreach($itens as $o):
  ?>
    <option value="<?=$o->$keyFieldName?>" <?=($selectedValue == $o->$keyFieldName ? "selected" : "")?>><?=$o->$descriptionField?></option>
  <?endforeach;?>
  </select>
<?
}


/**
* Formats a string containing a date to a specified format.
* @param string $dateStr Date in string format Y/m/d or Y-m-d
* @param string $foramt Format to convert the date
*/
function formatDateStr($dateStr, $format=MasterController::displayDateFormat) {
  $timestamp = strtotime($dateStr);
  return date($format, $timestamp);      
}

/**
* Get a full date  in format "%d de %B de %Y" in Brazilian Portuguese
* @return string Return the formated date in Brazilian Portuguese, like "21 de Novembro de 2010"
*/
function getTranslatedDate() {
  $month = strftime('%B');
  switch($month) {
	  case "January":   $month = "Janeiro";    break;
	  case "February":  $month = "Fevereiro";   break;
	  case "March":     $month = "Março";     break;
	  case "April":     $month = "Abril";     break;
	  case "May":       $month = "Maio";       break;
	  case "June":      $month = "Junho";      break;
	  case "July":      $month = "Julho";      break;
	  case "August":    $month = "Agosto";    break;
	  case "September": $month = "Setembro"; break;
	  case "October":   $month = "Outubro";   break;
	  case "November":  $month = "Novembro";  break;
	  case "December":  $month = "Dezembro";  break;
  }
  return strftime('%d de ') . $month . strftime(' de %Y');
}

/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Application functions
 * @package SampleApp
 */


// Descomente este bloco para habilitar o suporte à URL's Encriptadas

/*
function getSessionKey($id='key'){
	if (Session::get("link_key_$id")=="")
		Session::set("link_key_$id", substr(crypt(date("U") . $id), -10));
	return Session::get("link_key_$id");
}

function link_encode($l){
	$lnk = unserialize($l);
	$lnk["chave"] = md5($l);
	$lnk = json_encode($lnk);
	$lnk = Crypt::Encrypt($lnk, getSessionKey());
	return $lnk . "/";
}

function link_decode($l){
	if (!$l) return;
	$l = Crypt::Decrypt($l, getSessionKey());
	$l = (array) json_decode($l);

	$chave = (isset($l['chave'])) ? $l['chave'] : '';
	unset ($l["chave"]);
	if (isset($l["pars"])) 
		$l["pars"] = (array)$l["pars"]; 
	$l = serialize($l);
	if ($chave == md5($l)) return $l;
	throw new Exception("ops!");
}
*/
