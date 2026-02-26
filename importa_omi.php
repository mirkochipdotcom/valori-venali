<?php
    ob_start();
	include("../config/config.php");
	include("session.php");
	include("../layout/agid_template/header.php");
	include("../lib/gitt/interface.php");
	include("../modules/functions.php");
	
	echo '<h2 class="contentheading" style="padding-top:0px; margin-top:0px" >Gestione Valori OMI</h2><br/>';

	if( array_key_exists( "elimina", $_POST ) && $_POST["elimina"]!="" ) {
		$sql = "DELETE FROM valori_omi WHERE Periodo='".addslashes($_POST["elimina"])."'";
		exec_sql( $sql );
	}
	
	if( array_key_exists( "file_csv", $_FILES )) { 
		$file = $_FILES["file_csv"]["tmp_name"];
		
		$sql = "SHOW FIELDS FROM valori_omi WHERE Field<>'id_valore'";
		$campi = array();
		$rs = exec_sql( $sql );
		foreach( $rs as $dato ) {
			$campi[ $dato["Field"] ] = 1;
		}
		
		$handle = fopen( $file, "r" );
		$header = fgets( $handle );
		$parti = explode( "-", $header );
		
		$Periodo = trim( $parti[1] );
		
		$id_record = 0;
		$caricati = 0;
		while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
			$id_record++;
			if( $id_record == 1 ) {
				$struttura = $data;
			} else {
			    $sql    = "INSERT INTO valori_omi( Periodo, ";
			    $values = ") VALUES ( '".addslashes( $Periodo )."',";
			    $primo_campo = true;

				foreach( $struttura as $id => $campo ) {
					if( array_key_exists( $campo, $campi )) {
				    	if( !$primo_campo ) {
				      		$sql .= ",";
				      		$values .= ",";
				      	} else $primo_campo = false;
				      	$sql    .= " ".$campo;
				      	$values .= " '".addslashes( $data[$id] )."'";
				   }
				}
				
				if( !$primo_campo ) {
					$sql .= $values . ")";
					exec_sql( $sql ) or die(mysql_error());
					$caricati++;
				}
			}
		}
		echo '<div class="alert alert-success">Caricati '.$caricati.' valori OMI</div>';
	}
	
    $sql = "SELECT DISTINCT Periodo FROM valori_omi";
    $rs = exec_sql( $sql );
    
    $primo = true;
    foreach( $rs as $dato ) {
    	if( $primo ) {
    		echo '<h2 class="contentheading" style="padding-top:10px; margin-top:20px" >Valori OMI Presenti in archivio</h2><br/>';
    		echo '<form action="'.$URL_BASE.'/admin/fogli_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
    		echo '<table class="table table-striped table-bordered"><thead><tr><th>Periodo</th><th style="width:100px"></th></thead><tbody>';
    		$primo = false;
    	}
    	echo '<tr>';
    	echo '<td>'.$dato["Periodo"].'</td>';
    	echo '<td><button class="btn btn-danger" name="elimina" value="'.$dato["Periodo"].'">Elimina</button></td>';
    	echo '</tr>';
    }
    if( !$primo )
    	echo '</tbody></table></form>';
    
    echo '<div class="container" style="padding:0px"><div class="col-12" style="background-color:wheat">';
    echo '<h4 class="contentheading" style="padding-top:10px" >Importa File CSV Sister (Portale Comuni)</h4><br/>';
	echo '<form action="'.$URL_BASE.'/admin/importa_omi.php" method="post" style="padding-bottom:50px" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '   <input type="file" class="form-control" autocomplete="file_csv" id="file_csv" name="file_csv" aria-describedby="file_csvHelp" placeholder="Inserire File">';
	echo '   <small id="file_csv" class="form-text text-muted">Inserire il File da importare</small>';
	echo '</div>';	
	echo '<button type="submit" class="btn btn-success" name="upload">Carica...</button>';
	echo '</form></div></div>';		
    echo '<br/><br/>';
    include("../layout/agid_template/footer.php");
	ob_end_flush();
?>