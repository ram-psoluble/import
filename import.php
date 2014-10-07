<?php

include_once("connex.php");

 $msg='Error : ';
if (($handle = fopen('data.csv', 'r')) !== FALSE) {
  
  $data = fgetcsv($handle,",");
  
  while (($data = fgetcsv($handle,",")) !== FALSE) {

    $client       = $data[0]; 
    $police       = $data[1]; 
    $rri          = $data[2]; 
    $type_contrat = $data[3]; 
    $code_insee   = $data[4]; 
    $flag         = $data[5];

    // Ajouter les nouveau clients
    $query_cmd = "select count(*) from t_client where clt_code='".$client."'";
    if($query = ($bdd->query($query_cmd))){
      if($query->fetchColumn()<1){
        $query_cmd = "insert into t_client(clt_code) values('".$client."')";
        $bdd->exec($query_cmd) or die($msg."<br />Imporssible d'ajouter le nouveau client!<br />");
      }
    }

    // Ajouter les nouvelles communes
    $query_cmd = "select count(*) from t_commune where com_code_insee='".$code_insee."'";
    if($query = ($bdd->query($query_cmd))){
      if($query->fetchColumn()<1){
        $query_cmd = "insert into t_commune(com_code_insee) values('".$code_insee."')";
        $bdd->exec($query_cmd) or die($msg."<br />Imporssible d'ajouter la nouvelle commune!<br />");
      }
    }

    // Types de contrats
    $query_cmd = "select count(*) from t_type_contrat where tc_code='".$type_contrat."'";
    if($query = ($bdd->query($query_cmd))){
      if($query->fetchColumn()<1){
        // Ajouter un nouveau type de contrat
        $query_cmd = "insert into t_type_contrat(tc_code) values('".$type_contrat."')";
        $bdd->exec($query_cmd) or die($msg."<br />Imporssible d'ajouter le nouveau type de contrat!<br />");
      }
    }
    
    // Contrats
    // --------
    $query_cmd = "select count(*) from t_contrat where ctr_police=".$police;
    if($query = ($bdd->query($query_cmd))){
      if($query->fetchColumn()<1){
        // Ajouter les nouveaux contrats
        $query_cmd = "insert into t_contrat values('".$police."','".$client."','".$rri."','".$type_contrat."','".$code_insee."','".$flag."')";
        $bdd->exec($query_cmd) or die($msg."<br />Imporssible d'ajouter un contrat!<br />");
      }else{
        // Modifier les contrats existants.
        $query_cmd = "select * from t_contrat where ctr_police='".$police."'";
        $query = $bdd->query($query_cmd);
        $donnees = $query->fetch();
        $donnees["ctr_rri"];
        $donnees["ctr_code_insee"];

        if($donnees['ctr_rri']!=$rri or $donnees['ctr_code_insee']!=$code_insee){
          $query_cmd = "update t_contrat set ctr_rri=".$rri.", ctr_code_insee='".$code_insee."' where ctr_police=".$police;
          $bdd->exec($query_cmd) or die($msg."<br />Imporssible de modifier le contrat!<br />");
        }
      }
    }
  }

  fclose($handle);
}else{
    $msg = "Imporssible de lire le fichier";
  }
?>
