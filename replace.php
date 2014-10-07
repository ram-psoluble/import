<?php
if (($handle_brut= fopen('data_new.csv', 'r')) !== FALSE) {
  if (($handle = fopen('data_new2.csv', 'a')) !== FALSE) {
    while (($data = fgetcsv($handle_brut)) !== FALSE) {
      fputs($handle,str_replace(",", ";",$data[0])."\n");
    }
  }
}else{
    echo "Imporssible de le nouveau fichier";
  }
?>