<?php
session_start();

    $ruta_raiz = "."; 
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");


foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

if(isset($_GET["tipo_carp"]))  $tipo_carp = $_GET["tipo_carp"];

$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$tip3Nombre     = $_SESSION["tip3Nombre"];
$tip3desc       = $_SESSION["tip3desc"];
$tip3img        = $_SESSION["tip3img"];
$tpNumRad       = $_SESSION["tpNumRad"];
$tpPerRad       = $_SESSION["tpPerRad"];
$tpDescRad      = $_SESSION["tpDescRad"];
$tip3Nombre     = $_SESSION["tip3Nombre"];
$ESTILOS_PATH   = $_SESSION["ESTILOS_PATH"];

if(!isset($carpetano)){
    $carpetano = "";
}

$carpeta=$carpetano;

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");

include("$ruta_raiz/class_control/Param_admin.php"); 
$param=Param_admin::getObject($db,'%','ALERT_FUNCTION');


require("include/xajax/xajax.inc.php");
$xajax = new xajax(); 
$xajax->registerExternalFunction("updateInBox", "inBox_xajax.php");
$xajax->registerExternalFunction("updateFolders", "inBox_xajax.php");
//$xajax->debugOn();
$xajax->processRequests();
?>
<html>
<head>
<link rel="stylesheet" href="<?=$ruta_raiz."/estilos/".$_SESSION["ESTILOS_PATH"]?>/orfeo.css">
<script type="text/javascript" language="javascript">
    
/* 	FUNCION QUE MUESTRA LA VENTANA DE NOVEDADES DE USUARIO
	Busca los radicados de entrada o internos que le llegaron al usuario.
	Además los documentos que le devolvieron,
	le reasignaron, le informaron, le dieron visto bueno.
*/
/*
 *	La funcion updateFolders es la encargada de verificar
	los contenidos de las carpetas del usuario que se
	encuentra en la sesion actual,

	El intervalo de tiempo para hacer estas consultas se
	define en la funcion setTimeout
 */
function showInBox()
{   /*xajax_updateInBox('<?php echo $_SESSION['usua_doc']?>');
    setTimeout(showInBox, 10000); */
}

// refresca las carpetas de documentos
function updateFolders()
{
    xajax_updateFolders('<?php echo $krd ?>');
    setTimeout(updateFolders, 10000);
}
</script>
<?php
$xajax->printJavascript('include/xajax/'); 
?><script type="text/javascript">
// Variable que guarda la �ltima opci�n de la barra de herramientas de funcionalidades seleccionada
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage()
{
    var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array;
    var x;
    x = (a.length-2);
    for(i=0;i<x;i+=3)
    {
        if ((x=MM_findObj(a[i]))!=null)
        {   document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];  }
    }
}

selecMenuAnt = -1;
swVePerso    = 0;
numPerso     = 0;

function cambioMenu(img){

		MM_swapImage('plus' + img,'','imagenes/menuraya.gif',1);

	if (selecMenuAnt!=-1 && img!=selecMenuAnt)
		MM_swapImage('plus' + selecMenuAnt,'','imagenes/menu.gif',1);
	selecMenuAnt = img;

	if (swVePerso==1 && numPerso!=img){
		document.getElementById('carpersolanes').style.display="none";
		MM_swapImage('plus' + numPerso,'','imagenes/menu.gif',1);
		swVePerso=0;
	}
}
function verPersonales(id){
    
    mostrado=0;
    elem = document.getElementById(id);
    if(elem.style.display=="block")mostrado=1;
    elem.style.display="none";
    if(mostrado!=1)elem.style.display="block";
}
</script>
</head>
<body onload="<? if($param->PARAM_VALOR=='1'){ ?> showInBox();<? } ?>"><form action=correspondencia.php method="get">
<?php
$fechah = date("dmy") . "_" . time("hms");
$carpeta = $carpetano;
// Cambia a Mayuscula el login-->krd -- Permite al usuario escribir su login en mayuscula o Minuscula
$numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;
$fechah=date("dmy") . time("hms");
//Realiza la consulta del usuarios y de una vez cruza con la tabla dependencia
$isql = "select a.*,b.depe_nomb from usuario a,dependencia b
           where a.depe_codi=b.depe_codi
           AND USUA_LOGIN ='$krd' ";
$rs = $db->query($isql);
$phpsession = session_name()."=".session_id();
echo "<font size=1 face=verdana>";
// Valida Longin y contraseña encriptada con funcion md5()
if(trim($rs->fields["USUA_LOGIN"])==trim($krd))
{
    $contraxx=$rs->fields["USUA_PASW"];
	if (trim($contraxx)){
		$codusuario      = $rs->fields["USUA_CODI"];
		$dependencianomb = $rs->fields["DEPE_NOMB"];
		$fechah          = date("dmy") . "_" . time("hms");
		$contraxx        = $rs->fields["USUA_PASW"];
		$nivel           = $rs->fields["CODI_NIVEL"];
		$iusuario        = " and us_usuario                 = '$krd'";
		$perrad          = $rs->fields["PERM_RADI"];
		//Adicionado as contador
		// si el usuario tiene permiso de radicar el prog. muestra los iconos de radicaci�
		include "$ruta_raiz/menu/radicacion.php";

		// Esta consulta selecciona las carpetas Basicas de DocuImage que son extraidas de la tabla Carp_Codi
		$isql ="select CARP_CODI,CARP_DESC from carpeta order by carp_codi ";
		$rs = $db->query($isql);
		$addadm = "";
?>
<table border="0" cellpadding="0" cellspacing="0" width="160">
	<!-- fwtable fwsrc="Sin t�tulo" fwbase="menu.gif" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
<tr>
	<td colspan="2"><a href="#" onClick="window.location.reload()">
	<img name="menu_r3_c1" src="./imagenes/menu_r5_c1.gif" alt="Presione para actualizar las carpetas." width="148" height="26" border="0" ></a></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td valign="top">
	<table width="150" border="0" cellpadding="0" cellspacing="0" class="eMenu">
<tr>
<td valign="top">
<table width="150"  border="0" cellpadding="0" cellspacing="3" >
<?php
		while(!$rs->EOF)
		{
			if(!isset($data))   $data = "NULL";
			if($data=="")   $data = "NULL";
			$numdata = trim($rs->fields["CARP_CODI"]);

			$sqlCarpDep = "select SGD_CARP_DESCR from SGD_CARP_DESCRIPCION where SGD_CARP_DEPECODI = $dependencia and SGD_CARP_TIPORAD = $numdata";

			$rsCarpDesc = $db->query($sqlCarpDep);
			$descripcionCarpeta =  $rsCarpDesc->fields["SGD_CARP_DESCR"];
			( $descripcionCarpeta ) ?   $data = $descripcionCarpeta : $data = trim($rs->fields["CARP_DESC"]);

			if($numdata==11)
			{   // Se realiza la cuenta de radicados en Visto Bueno VoBo
				if($codusuario ==1)
				{
					$isql="select count(1) as CONTADOR from radicado r, sgd_dir_drecciones dir
						where r.carp_per=0 and r.carp_codi=$numdata
						and r.radi_nume_radi = dir.radi_nume_radi
						and dir.sgd_dir_tipo=1
						and  r.radi_depe_actu=$dependencia
						and r.radi_usua_actu=$codusuario";
				}
				else
				{
					$isql="select count(1) as CONTADOR from radicado r, sgd_dir_drecciones dir
						where r.carp_per=0
						and r.radi_nume_radi = dir.radi_nume_radi
                                                and dir.sgd_dir_tipo=1
						and r.carp_codi=$numdata
						and r.radi_depe_actu=$dependencia
						and r.radi_usu_ante='$krd'";
				}
				$addadm = "&adm=1";
			}
			else
			{
				$isql="select count(1) as CONTADOR from radicado r, sgd_dir_drecciones dir
					where r.carp_per=0 and r.carp_codi=$numdata
					and r.radi_nume_radi = dir.radi_nume_radi
                                                and dir.sgd_dir_tipo=1
					and  r.radi_depe_actu=$dependencia
					and r.radi_usua_actu=$codusuario  ";
				$addadm = "&adm=0";
			}
			($carpeta==$numdata)? $imagen="folder_open.gif" : $imagen="folder_cerrado.gif";
			$flag = 0;

			$rs1 = $db->query($isql);
			$numerot = $rs1->fields["CONTADOR"];
			if ($flag==1)   echo "$isql";
?>
	<tr  valign="middle">
	<!--  <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>'  name="plus<?=$i?>"></td> -->
	  <td width="125">
	  <a onclick="cambioMenu(<?=$i?>);" href='cuerpo.php?<?=$phpsession?>&adodb_next_page=1&fechah=<?php echo "$fechah&nomcarpeta=$data&carpeta=$numdata&tipo_carpt=0&adodb_next_page=1"; ?>' class="menu_princ" target="mainFrame" alt="Seleccione una Carpeta"><? echo "$data($numerot)";?></a>
	</td>
	</tr>
<?php
			$i++;
			$rs->MoveNext();
		}
		/**
		 * PARA ARCHIVOS AGENDADOS NO VENCIDOS
		 *  (Por. SIXTO 20040302)
		 */
		$sqlFechaHoy=$db->conn->DBTimeStamp(time());
		$sqlAgendado=" and (agen.SGD_AGEN_FECHPLAZO >= ".$sqlFechaHoy.")";
		$isql="select count(1) as CONTADOR from SGD_AGEN_AGENDADOS agen
			where  usua_doc='$usua_doc' and agen.SGD_AGEN_ACTIVO=1 $sqlAgendado";
		$rs=$db->query($isql);
		$num_exp = $rs->fields["CONTADOR"];
		$data="Agendados no vencidos";
?>
	<tr  valign="middle">
	<!-- <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>'  name="plus<?=$i?>"></td> -->
	<td width="125">
	<a onclick="cambioMenu(<?=$i?>);" href='cuerpoAgenda.php?<?=$phpsession?>&agendado=1&fechah=<?php echo "$fechah&nomcarpeta=$data&tipo_carpt=0"; ?>' class="menu_princ" target="mainFrame" alt="Seleccione una Carpeta">
	<? echo "Agendado($num_exp)";?>
	</a>
	</td>
	</tr>
<?php
		/**
		 * PARA ARCHIVOS AGENDADOS  VENCIDOS
		 *  (Por. SIXTO 20040302)
		 */
		$sqlAgendado=" and (agen.SGD_AGEN_FECHPLAZO <= ".$sqlFechaHoy.")";
		$isql="select count(1) as CONTADOR from SGD_AGEN_AGENDADOS agen
			where  usua_doc='$usua_doc' and agen.SGD_AGEN_ACTIVO=1 $sqlAgendado";
		$rs=$db->query($isql);
		$num_exp = $rs->fields["CONTADOR"];
		$data="Agendados vencidos";
		$i++;
?>
		<tr  valign="middle">
		<!-- <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>' name="plus<?=$i?>"></td> -->
		<td width="125">
			<a onclick="cambioMenu(<?=$i?>);" href='cuerpoAgenda.php?<?=$phpsession?>&agendado=2&fechah=<?php echo "$fechah&nomcarpeta=$data&&tipo_carpt=0&adodb_next_page=1"; ?>' class="menu_princ" target="mainFrame" alt="Seleccione una Carpeta">
			<? echo "Agendado Vencido(<font color='#990000'>$num_exp</font>)";?>
			</a>
		</td>
	</tr>
<?php
    // Coloca el mensaje de Informados y cuenta cuantos registros hay en informados
    $isql="select count(1) as CONTADOR from informados where depe_codi=$dependencia and usua_codi=$codusuario and info_conjunto=0  ";
    ($carpeta==$numdata and $tipo_carp=0) ? $imagen="folder_open.gif" : $imagen="folder_cerrado.gif";
    $rs1=$db->query($isql);
    if ($rs1){
        $numerot = $rs1->fields["CONTADOR"];
    }else{
        $numerot = 0;
    }
    $i++;
    $data="Documentos De Informacion";
?>
  <tr  valign="middle">
  <!-- <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>' name="plus<?=$i?>"></td> -->
  <td width="125">
  <a onclick="cambioMenu(<?=$i?>);" href='cuerpoinf.php?<?=$phpsession?>&<?= "mostrar_opc_envio=1&orderNo=2&fechaf=$fechah&carpeta=8&nomcarpeta=Informados&orderTipo=desc&adodb_next_page=1"; ?>' class="menu_princ" target="mainFrame" alt='Documentos De Informacion' title="Documentos De Informacion">
  Informados (<?=$numerot?>) <? $i++; ?>
  </a>
  </td>
  </tr>

<?php
    // Coloca el mensaje de Tramite Conjunto Informados y cuenta cuantos registros hay en esta seleccion
    $isql="select count(1) as CONTADOR from informados where depe_codi=$dependencia and usua_codi=$codusuario and info_conjunto>=1 ";
    ($carpeta==$numdata and $tipo_carp=0) ? $imagen="folder_open.gif" : $imagen="folder_cerrado.gif";
    $rs1=$db->query($isql);
     if ($rs1){
        $numerot = $rs1->fields["CONTADOR"];
    }else{
        $numerot = 0;
    }
    $i++;
    $data="Tramite Conjunto";
if($numerot>=1){
?>
  <tr  valign="middle">
  <!-- <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>' name="plus<?=$i?>"></td> -->
  <td width="125">
  <a onclick="cambioMenu(<?=$i?>);" href='tx/cuerpoInfConjunto.php?<?=$phpsession?>&<?= "mostrar_opc_envio=1&orderNo=2&fechaf=$fechah&carpeta=66&nomcarpeta=Informados&orderTipo=desc&adodb_next_page=1"; ?>' class="menu_princ" target="mainFrame" alt='<?=$data?>' title="Tramite Conjunto">
  Tramite Conjunto (<?=$numerot?>) <? $i++; ?>
  </a>
  </td>
  </tr>


<?php
}

    /**
      * Carpeta de transacciones realizadas por el usuario
      * @autor Jairo Losada
      * @fecha Marzo del 2009
      * @version Orfeo 3.7.2
      * @licencia GNU/GPL
      *
      */
    $data="Ultimas Transacciones del Usuario";
?>
  <tr  valign="middle">
  <!-- <td width="25"><img src="imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>'  name="plus<?=$i?>"></td> -->
  <td width="125">
	  <a onclick="cambioMenu(<?=$i?>);" href='cuerpoTx.php?<?=$phpsession?>&fechah=<?php echo "$fechah&nomcarpeta=$data&tipo_carpt=0"; ?>' class="menu_princ" target="mainFrame" alt="Transaccines del Usuario">
	  Transacciones
	  </a>
  </td>
  </tr>
  <tr  valign="middle">
  <?  $data="Despliegue de Carpetas Personales";  ?>
  <!-- <td width="25">
		  <img src="./imagenes/menu.gif" width="15" height="18" alt='<?=$data ?> ' title='<?=$data ?>' name="plus<?=$i?>">
  </td> -->
   <tr  valign="middle">
<?
  $numeroP = 0;
  include ("include/query/queryCuerpoPrioritario.php");
  echo "<!-- $isqlPrioritario -->";
  $rsP = $db->conn->query($isqlPrioritario);
  $numeroP = $rsP->fields["NUMEROP"];
   
  if($numeroP >=1) $clasePrioritarios="titulosError"; else $clasePrioritarios="menu_princ";
?>

  <tr>
  <td width="125">
   <a onclick="cambioMenu(<?=$i?>);" href='cuerpoPrioritario.php?<?=$phpsession?>&fechah=<?php echo "$fechah&nomcarpeta=$data&tipo_carpt=0"; ?>' class="<?=$clasePrioritarios?>" target="mainFrame" alt="Prioritarios">
    Prioritarios (<?=$numeroP?>)
    </a>
  </td>
  </tr>

  <tr>
  <td width="125">
	  <a onclick="verPersonales('tabla1')" href='#' class="menu_princ"  alt="Despliegue de Carpetas Personales" title="Despliegue de Carpetas Personales" name="marcaPersonales">
	  PERSONALES
	  </a>
  </td>
  </tr>
  </table>
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="cacac9" id="tabla1" style="display: none">
	  <tr>
	  <td>
		  <a class="vinculos" href="crear_carpeta.php?<?=$phpsession?>&krd=<?=$krd?>&<? echo "fechah=$fechah&adodb_next_page=1"; ?>" class="menu_princ" target='mainFrame' alt='Creacion de Carpetas Personales'  title='Creacion de Carpetas Personales'>
		  <font size=2>Nueva carpeta</font>
		  </a>
	  </td>
	  </tr>
<?php
    // BUSCA LAS CARPETAS PERSONALES DE CADA USUARIO Y LAS COLOCA contando el numero de documentos en cada carpeta.
    $isql ="select distinct CODI_CARP,DESC_CARP,NOMB_CARP from carpeta_per where usua_codi=$codusuario and depe_codi=$dependencia order by codi_carp  ";
    $rs=$db->query($isql);
    while(!$rs->EOF) 
    {
	    if($data=="")   $data = "NULL";
	    $data = trim($rs->fields["NOMB_CARP"]);
	    $numdata =  trim($rs->fields["CODI_CARP"]);
	    $detalle = trim($rs->fields["DESC_CARP"]);
	    $data = trim($rs->fields["NOMB_CARP"]) ;
	    $isql="select count(1) as CONTADOR from radicado where carp_per=1 and carp_codi=$numdata and  radi_depe_actu=$dependencia and radi_usua_actu=$codusuario ";
	    $rs1=$db->query($isql);
	    $numerot = $rs1->fields["CONTADOR"];
	    $datap = "$data(Personal)";
?>
<tr>
	<td height="18"><a href="cuerpo.php?<?=$phpsession ?>&<? echo "fechah=$fechah&tipo_carp=1&carpeta=$numdata&nomcarpeta=$data "; ?>(Personal)<? echo ""; ?>" alt="<?=$detalle?>" title="<?=$detalle?>" class="menu_princ" target="mainFrame">	<? echo "$data($numerot)";?></a> </td>
</tr>
<?php
	    $rs->MoveNext();
    }
?>
	</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
<?php
	}
}
//*********************TRANSACCIONES DEL CURSOR DE CONSULTA PRIMARIA**************************************************************************************************
(!$db->imagen()) ?  $logo = "logoEntidad.png" : $logo = $db->imagen();
?>
<?
include "$ruta_raiz/menu/menuPrimero.php";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="t_bordeVerde">
<tr align="center">
<!-- <td height="35"><img width=80 src='<?=$logo?>' alt="Logo"></td> -->
</tr>
<tr align="center">
<td height="20">
		<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
Equipo:
<?php	// Coloca de direccion ip del equipo desde el cual se esta entrando a la pagina.
					echo $_SERVER['REMOTE_ADDR'];
?></font>
	</td>
</tr>
</table>
</form>

</body>
</html>
