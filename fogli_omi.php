<?php
    ob_start();
	include("../config/config.php");
	include("session.php");
	include("../layout/header.php");
	include("../lib/gitt/interface.php");
	include("../modules/functions.php");
	
	if( array_key_exists( "zona_omi", $_POST ) && array_key_exists( "foglio_catastale", $_POST )) {
		$sql = "INSERT INTO fogli_zone_omi( foglio_catastale, zona_omi ) VALUES('".$_POST["foglio_catastale"]."', '".$_POST["zona_omi"]."')";
		mysql_query( $sql );
	}	
	
	if( array_key_exists( "elimina", $_POST ) ) {
		$sql = "DELETE FROM fogli_zone_omi WHERE foglio_catastale='".$_POST["elimina"]."'";
		mysql_query( $sql );
	}
	
    $sql = "SELECT * FROM fogli_zone_omi";
    $rs = mysql_query( $sql );
    
    $primo = true;
    while( $dato = mysql_fetch_array( $rs )) {
    	if( $primo ) {
    		echo '<h2 class="contentheading" style="padding-top:10px; margin-top:20px" >Valori OMI Presenti in archivio</h2><br/>';
    		echo '<form action="'.$URL_BASE.'/admin/fogli_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
    		echo '<table class="table table-striped table-bordered"><thead><tr><th>Foglio Catastale</th><th>Zona OMI</th><th style="width:100px"></th></thead><tbody>';
    		$primo = false;
    	}
    	echo '<tr>';
    	echo '<td>'.$dato["foglio_catastale"].'</td>';
    	echo '<td>'.$dato["zona_omi"].'</td>';
    	echo '<td><button class="btn btn-danger" name="elimina" value="'.$dato["foglio_catastale"].'">Elimina</button></td>';
    	echo '</tr>';
    }
    if( !$primo )
    	echo '</tbody></table></form>';
    
    echo '<div class="container" style="padding:0px"><div class="col-12" style="background-color:wheat">';
    echo '<h2 class="contentheading" style="padding-top:10px" >Carica Nuovo Abbinamento Foglio -> Zona OMI</h2><br/>';
	echo '<form action="'.$URL_BASE.'/admin/fogli_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '   <label for="foglio_catastale">Foglio Catastale</label>';
	echo '   <input type="text" class="form-control" autocomplete="foglio_catastale" id="foglio_catastale" name="foglio_catastale" aria-describedby="file_csvHelp" placeholder="Inserire Foglio Catastale">';
	echo '   <small id="file_csvHelp" class="form-text text-muted">Inserire il Foglio Catastale</small>';
	echo '</div>';	
	echo '<div class="form-group">';
	echo '   <label for="zona_omi">Zona OMI</label>';
	echo '   <select class="form-control" autocomplete="zona_omi" id="zona_omi" name="zona_omi" aria-describedby="zona_omiHelp" placeholder="Inserire la Zona OMI">';
	$sql = "SELECT DISTINCT Zona FROM valori_omi";
	$rs_zone = mysql_query( $sql );
	while( $dati_zone = mysql_fetch_array( $rs_zone )) {
		echo '<option>'.$dati_zone["Zona"].'</option>';
	}
	echo '   </select>';
	echo '   <small id="zona_omiHelp" class="form-text text-muted">Inserire la Zona OMI</small>';
	echo '</div>';	
	echo '<button type="submit" class="btn btn-success" name="upload">Carica...</button>';
	echo '</form></div></div>';		
	
	echo '<br/><br/>';
    include("../layout/footer.php");
	ob_end_flush();
?>
