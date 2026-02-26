<?php
    ob_start();
	include("../config/config.php");
	include("session.php");
	include("../layout/agid_template/header.php");
	include("../lib/gitt/interface.php");
	include("../modules/functions.php");
	
	echo '<h2 class="contentheading" style="padding-top:0px; margin-top:0px" >Gestione Coefficienti</h2><br/>';
	
	if( array_key_exists( "elimina", $_POST ) ) {
		$sql = "DELETE FROM omi_destinazione_urbanistica WHERE id_destinazione='".$_POST["elimina"]."'";
		exec_sql( $sql );
	}
	
	if( array_key_exists( "upload", $_POST )) {
		$sql  = "INSERT INTO omi_destinazione_urbanistica( destinazione, coefficiente_destinazione, Cod_Tip, Stato, Valore )";
		list( $cod_tip, $stato ) = explode( "_", $_POST["zona_omi"] );
		$sql .= " VALUES('".$_POST["destinazione"]."', '".$_POST["coefficiente"]."', '".$cod_tip."', '".$stato."', '".$_POST["valore"]."')";
		exec_sql( $sql );
	}
	
    $sql = "SELECT * FROM omi_destinazione_urbanistica";
    $rs = exec_sql( $sql );
    
    $primo = true;
    
    $valori = array( 1 => "Minimo", 2 => "Medio", 3 => "Massimo" );
    
    $sql = "SELECT DISTINCT Cod_Tip, Descr_Tipologia FROM valori_omi order by Descr_Tipologia, Stato DESC";
	$rs_zone = exec_sql( $sql );
	
	$descrizioni = array();
	
	foreach( $rs_zone as $dato ) {
		$descrizioni[$dato["Cod_Tip"]] = $dato["Descr_Tipologia"];
	}
	
    foreach( $rs as $dato ) {
    	if( $primo ) {
    		echo '<h2 class="contentheading" style="padding-top:10px; margin-top:20px" >Valori OMI Presenti in archivio</h2><br/>';
    		echo '<form action="'.$URL_BASE.'/admin/parametri_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
    		echo '<table class="table table-striped table-bordered"><thead><tr><th>Destinazione Urbanistica</th><th>Coefficiente</th><th>Tipologia Valore</th><th>Stato</th><th>Valore</th><th style="width:100px"></th></thead><tbody>';
    		$primo = false;
    	}
    	echo '<tr>';
    	echo '<td>'.$dato["destinazione"].'</td>';
    	echo '<td>'.$dato["coefficiente_destinazione"].'</td>';
    	echo '<td>'.$descrizioni[$dato["Cod_Tip"]].'</td>';
    	echo '<td>'.$dato["Stato"].'</td>';
    	echo '<td>'.$valori[$dato["valore"]].'</td>';
    	echo '<td><button class="btn btn-danger" name="elimina" value="'.$dato["id_destinazione"].'">Elimina</button></td>';
    	echo '</tr>';
    }
    if( !$primo )
    	echo '</tbody></table></form>';
	
	    
    echo '<div class="container" style="padding:0px"><div class="col-12" style="background-color:wheat">';
    echo '<h2 class="contentheading" style="padding-top:10px" >Carica Nuovo Coefficiente di Destinazione Urbanistica</h2><br/>';
	echo '<form action="'.$URL_BASE.'/admin/parametri_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '   <label for="destinazione">Destinazione Urbanistica</label>';
	echo '   <input type="text" class="form-control" autocomplete="destinazione" id="destinazione" name="destinazione" aria-describedby="file_csvHelp" placeholder="Inserire Zona PRG">';
	echo '   <small id="file_csvHelp" class="form-text text-muted">Inserire la Zona di Piano Regolatore</small>';
	echo '</div>';		
	echo '<div class="form-group">';
	echo '   <label for="coefficiente">Coefficiente</label>';
	echo '   <input type="text" class="form-control" autocomplete="coefficiente" id="coefficiente" name="coefficiente" aria-describedby="file_csvHelp" placeholder="Inserire il Coefficiente di Destinazione">';
	echo '   <small id="file_csvHelp" class="form-text text-muted">Inserire il coefficiente (Valore tra 0 ed 1. Utilizzare il punto "." e non la virgola "," per separare i decimali)</small>';
	echo '</div>';	
	echo '<div class="form-group">';
	echo '   <label for="zona_omi" class="active">Tipologia Valore OMI</label>';
	echo '   <select class="form-control" autocomplete="zona_omi" id="zona_omi" name="zona_omi" aria-describedby="zona_omiHelp" placeholder="Inserire la Zona OMI">';
	$sql = "SELECT DISTINCT Cod_Tip, Descr_Tipologia,Stato FROM valori_omi order by Descr_Tipologia, Stato DESC";
	$rs_zone = exec_sql( $sql );
	foreach( $rs_zone as $dati_zone ) {
		echo '<option value="'.$dati_zone["Cod_Tip"].'_'.$dati_zone["Stato"].'">'.$dati_zone["Descr_Tipologia"]." (".$dati_zone["Stato"].')</option>';
	}
	echo '   </select>';
	echo '   <small id="zona_omiHelp" class="form-text text-muted">Inserire la Zona OMI</small>';
	echo '</div>';		echo '<div class="form-group">';
	echo '   <label for="valore" class="active">Valore da Utilizzare</label>';
	echo '   <select class="form-control" autocomplete="valore" id="valore" name="valore" aria-describedby="zona_omiHelp" placeholder="Inserire il Valore da Utilizzare">';
	echo '		<option value="2">Medio</option>';
	echo '		<option value="1">Minimo</option>';
	echo '		<option value="3">Massimo</option>';	
	echo '   </select>';
	echo '   <small id="zona_omiHelp" class="form-text text-muted">Inserire il valore da utilizzare</small>';
	echo '</div>';	
	echo '<button type="submit" class="btn btn-success" name="upload">Carica...</button>';
	echo '</form></div></div>';	
	
	echo '<br/><br/>';
    include("../layout/agid_template/footer.php");
	ob_end_flush();
?>