<?php


$wochenstarttag = $_REQUEST['start_ts'];
$dienstagts = $wochenstarttag + 86400 * 1;
$mittwochts = $wochenstarttag + 86400 * 2;
$donnerstagts = $wochenstarttag + 86400 * 3;
$freitagts = $wochenstarttag + 86400 * 4;

$woche_ts_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts);

$tage_ts_woche_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts, $freitagts);

           $kunden_kitafino = $kundeVerwaltung->findeAlleMitKundennummerKitafino();
           
           
$time_start = microtime(true);
          //  $bestellzahlen = ermittleZahlenZuKundeWoche2($tage_ts_woche_array, $kunden_kitafino, $pids_sbar_speisewahl);
            
            
           $bestellzahlen = ermittleZahlenZuKundeWoche($tage_ts_woche_array, $kunden_kitafino, $pids_sbar_speisewahl);
       
$time_end = microtime(true);
$laufzeit = round($time_end - $time_start,2);

/*$log  = date("d.m.Y H:i:s").';'.$laufzeit.';'.$sorting.';'.$max_sent_mails.';'.$sent_mails.PHP_EOL;
file_put_contents('/var/www/vhosts/kitafino.de/logs/sendorders/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);*/

echo '<pre>';
          var_dump($laufzeit);
          var_dump($bestellzahlen);
          echo '</pre>';
?>
