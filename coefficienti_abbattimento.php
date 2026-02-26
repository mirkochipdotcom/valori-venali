<?php
    ob_start();
	include("../config/config.php");
	include("session.php");
	include("../layout/header.php");
	include("../lib/gitt/interface.php");
	include("../modules/functions.php");
	
	echo '<h2 class="contentheading" style="padding-top:0px; margin-top:0px" >Gestione Coefficienti</h2><br/>';
	
	if( array_key_exists( "elimina", $_POST ) ) {
		$sql = "DELETE FROM omi_abbattimenti WHERE id_coefficiente='".$_POST["elimina"]."'";
		mysql_query( $sql );
	}

	if( array_key_exists( "upload", $_POST )) {
		$sql  = "INSERT INTO omi_abbattimenti( descrizione, valore )";
		$sql .= " VALUES('".addslashes($_POST["descrizione"])."', '".$_POST["coefficiente"]."')";
		mysql_query( $sql ) or die(mysql_error());
	}
	
    $sql = "SELECT * FROM omi_abbattimenti";
    $rs = mysql_query( $sql );
    
    $primo = true;
	
    while( $dato = mysql_fetch_array( $rs )) {
    	if( $primo ) {
    		echo '<h2 class="contentheading" style="padding-top:10px; margin-top:20px" >Valori OMI Presenti in archivio</h2><br/>';
    		echo '<form action="'.$URL_BASE.'/admin/coefficienti_abbattimento.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
    		echo '<table class="table table-striped table-bordered"><thead><tr><th>Descrizione</th><th>Coefficiente</th><th style="width:100px"></th></thead><tbody>';
    		$primo = false;
    	}
    	echo '<tr>';
    	echo '<td>'.$dato["descrizione"].'</td>';
    	echo '<td>'.$dato["valore"].'</td>';
    	echo '<td><button class="btn btn-danger" name="elimina" value="'.$dato["id_coefficiente"].'">Elimina</button></td>';
    	echo '</tr>';
    }
    if( !$primo )
    	echo '</tbody></table></form>';
	
	    
    echo '<div class="container" style="padding:0px"><div class="col-12" style="background-color:wheat">';
    echo '<h2 class="contentheading" style="padding-top:10px" >Carica Nuovo Coefficiente di Destinazione Urbanistica</h2><br/>';
	echo '<form action="'.$URL_BASE.'/admin/coefficienti_abbattimento.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '   <label for="destinazione">Descrizione</label>';
	echo '   <input type="text" class="form-control" autocomplete="descrizione" id="descrizione" name="descrizione" aria-describedby="file_csvHelp" placeholder="Inserire la descrizione del Coefficiente">';
	echo '   <small id="file_csvHelp" class="form-text text-muted">Inserire la Descrizione del Coefficiente</small>';
	echo '</div>';		
	echo '<div class="form-group">';
	echo '   <label for="coefficiente">Coefficiente</label>';
	echo '   <input type="text" class="form-control" autocomplete="coefficiente" id="coefficiente" name="coefficiente" aria-describedby="file_csvHelp" placeholder="Inserire il Coefficiente di Destinazione">';
	echo '   <small id="file_csvHelp" class="form-text text-muted">Inserire il coefficiente (Valore tra 0 ed 1. Utilizzare il punto "." e non la virgola "," per separare i decimali)</small>';
	echo '</div>';	
	echo '<button type="submit" class="btn btn-success" name="upload">Carica...</button>';
	echo '</form></div></div>';	
	
	echo '<br/><br/>';
    include("../layout/footer.php");
	ob_end_flush();
?>