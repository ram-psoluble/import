<?php
if (($handle_new= fopen('data_new.csv', 'r')) !== FALSE) {
  if (($handle_old = fopen('data_old.csv', 'r')) !== FALSE) {

    $data_new = array();
    $data = fgetcsv($handle_new);
    while (($data = fgetcsv($handle_new)) !== FALSE) {
      $data_new[] = array(
        'police'        => $data[1],
        'client'        => $data[0],
        'rri'           => $data[2],
        'type_contrat'  => $data[3],
        'code_insee'    => $data[4],
        'flag'          => $data[5],
        );
    }
    
    fclose($handle_new);

    $data_old = array();
    $data = fgetcsv($handle_old);
    while (($data = fgetcsv($handle_old)) !== FALSE) {
      $data_old[] = array(
        'police'        => $data[1],
        'client'        => $data[0],
        'rri'           => $data[2],
        'type_contrat'  => $data[3],
        'code_insee'    => $data[4],
        'flag'          => $data[5],
        );
    }

    fclose($handle_old);

    if (($handle_diff_actif = fopen('data_diff_actif.csv', 'a')) !== FALSE) {
      foreach ($data_new as $new) {
        $exist = "";
        foreach ($data_old as $old) {
          $diffrence = array_diff($new, $old);
          if(sizeof($diffrence)<=0){
            $exist="true";
          }
        }
        if(empty($exist)){
          $str = $new['client'].",".$new['police'].",".$new['rri'].",".$new['type_contrat'].",".$new['code_insee'].",".$new['flag']."\n";
          fputs($handle_diff_actif,$str);
          print '<pre>';
          print_r($new);
          print '</pre>';
        }
      }
      fclose($handle_diff_actif);

      echo "Inactif";

      if (($handle_diff_inactif = fopen('data_diff_inactif.csv', 'a')) !== FALSE) {
        foreach ($data_old as $old) {
          $exist = "";
          foreach ($data_new as $new) {
            $diffrence = array_diff($old,$new);
            if(sizeof($diffrence)<=0){
              $exist="true";
            }
          }
          if(empty($exist)){
            $str = $old['client'].",".$old['police'].",".$old['rri'].",".$old['type_contrat'].",".$old['code_insee'].",".$old['flag']."\n";
            fputs($handle_diff_inactif,$str);
            print '<pre>';
            print_r($old);
            print '</pre>';
          }
        }
        fclose($handle_diff_inactif);
      }
    }
  }else{
    echo "Imporssible de l'ancien fichier de données";
  }
}else{
    echo "Imporssible de le nouveau fichier";
  }
?>