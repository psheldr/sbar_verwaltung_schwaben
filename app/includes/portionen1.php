
                <tr class="<?php echo $highlight_class ?>" style="">

                    <td>
                        <input type="hidden" name="portionenaenderung_id[]" value="<?php echo $portionenaenderung->getId() ?>" />
<?php echo $rowcount; ?>
                        <?php echo $kunde->getName(); ?> <?php if ($kunde->getAnzahlSpeisen() > 1) { ?> - Speise 1<?php } ?>
                        <?php echo $echo_mo;
                        ?>
                        <?php
                        // kitafino CONNECT

                        $readonly = '';
                        $alert_kitafino_zahlen = '';
                        if ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '') {
                            $alert_kitafino_zahlen = 'border: 2px solid red;';
                            echo '<strong style="color: red;font-size: 11px;">Achtung! Fehler: Zahlen konnten keinen Gruppen zugeordnet werden. In kitafino prüfen und Zahlen manuell eintragen.</strong>';
                        }
                        if ($kunde->getKundennummer()) {
                            $readonly = 'readonly';
                            $projekt_id_db = $kunde->getKundennummer();
                            // include 'functions/connect_db.php';
                            // $count_orders = ermittleZahlenZuKundeTag($wochenstarttag, $projekt_id_db);
                            $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][0];
                            //$count_orders = $bestellzahlen[0];
                            ?>
                            <br/>
                            <img style="margin-top:5px;" src="images/kitafino_icon.png" /> <strong><?php echo $projekt_id_db ?></strong>

                            <?php
                            if ($portionen_mo != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
                                $alert_kitafino_zahlen = 'border: 2px solid red;';
                                $readonly = '';
                            }
                        }
                        ?>
                        <?php if ($kunde->getStaedtischerKunde()) { ?>
                            <br/>
                            <img style="margin-top:5px;max-width: 20px;" src="images/nbg_icon.png" />

                        <?php } ?>

                        <?php if ($kunde->getBioKunde()) { ?>
                            <br/>
                            <img style="margin-top:5px;max-width: 30px;" src="../images/biosiegel_small.jpg" />

                        <?php } ?>
                        <?php
                        ?>
                    </td>
                    <td>
                        <input style="<?php echo $alert_kitafino_zahlen ?>" class="portionen_input" type="text" name="portionen_mo[]" <?php echo $readonly ?> value="<?php echo $portionen_mo ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                        <?php
                        if ($portionen_mo != $standardportionen->getPortionenMo()) {
                            echo '<sup>1</sup> (' . $standardportionen->getPortionenMo() . ')';
                        }
                        ?>


                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="text" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                        <?php } ?>
                    </td>

                    <td><?php echo $kunde->getName(); ?> <?php if ($kunde->getAnzahlSpeisen() > 1) { ?> - Speise 1<?php } ?>
                        <?php echo $echo_di ?>
                        <?php
                        if ($_REQUEST['dev']) {
                            $alert_kitafino_zahlen = '';
                            $readonly = '';
                            if ($kunde->getKundennummer()) {
                                $readonly = 'readonly';
                                $projekt_id_db = $kunde->getKundennummer();
                                // include 'functions/connect_db.php';
                                // $count_orders = ermittleZahlenZuKundeTag($dienstagts, $projekt_id_db);
                                //$count_orders = $bestellzahlen[1];
                                $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][1];
                                ?>
                                <!--     <br/>
                                     <img  style="margin-top:5px;" src="images/kitafino_icon.png" /> <?php echo $projekt_id_db ?>     -->                      

                                <?php
                                if ($portionen_di != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
                                    $alert_kitafino_zahlen = 'border: 2px solid red;';
                                    $readonly = '';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <input style="<?php echo $alert_kitafino_zahlen ?>" class="portionen_input" type="text" name="portionen_di[]" <?php echo $readonly ?> value="<?php echo $portionen_di ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                        <?php
                        if ($portionen_di != $standardportionen->getPortionenDi()) {
                            echo '<sup>1</sup> (' . $standardportionen->getPortionenDi() . ')';
                        }
                        ?>
                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="text" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                        <?php } ?>
                    </td>

                    <td><?php echo $kunde->getName(); ?> <?php if ($kunde->getAnzahlSpeisen() > 1) { ?> - Speise 1<?php } ?>
                        <?php echo $echo_mi ?>

                        <?php
                        if ($_REQUEST['dev']) {
                            $alert_kitafino_zahlen = '';
                            $readonly = '';
                            if ($kunde->getKundennummer()) {
                                $readonly = 'readonly';
                                $projekt_id_db = $kunde->getKundennummer();
                                // include 'functions/connect_db.php';
                                // $count_orders = ermittleZahlenZuKundeTag($mittwochts, $projekt_id_db);
                                $count_orders = $bestellzahlen[2];
                                $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][2];
                                ?>
                                <!--   <br/>
                                  <img  style="margin-top:5px;" src="images/kitafino_icon.png" /> <?php echo $projekt_id_db ?>      -->                     

                                <?php
                                if ($portionen_mi != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
                                    $alert_kitafino_zahlen = 'border: 2px solid red;';
                                    $readonly = '';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <input style="<?php echo $alert_kitafino_zahlen ?>" class="portionen_input" type="text" name="portionen_mi[]" <?php echo $readonly ?> value="<?php echo $portionen_mi ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                        <?php
                        if ($portionen_mi != $standardportionen->getPortionenMi()) {
                            echo '<sup>1</sup> (' . $standardportionen->getPortionenMi() . ')';
                        }
                        ?>
                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders; ?>" />
                        <?php } ?>
                    </td>

                    <td><?php echo $kunde->getName(); ?> <?php if ($kunde->getAnzahlSpeisen() > 1) { ?> - Speise 1<?php } ?>
                        <?php echo $echo_do ?>
                        <?php
                        if ($_REQUEST['dev']) {
                            $alert_kitafino_zahlen = '';
                            $readonly = '';
                            if ($kunde->getKundennummer()) {
                                $readonly = 'readonly';
                                $projekt_id_db = $kunde->getKundennummer();
                                // include 'functions/connect_db.php';
                                // $count_orders = ermittleZahlenZuKundeTag($donnerstagts, $projekt_id_db);
                                $count_orders = $bestellzahlen[3];
                                $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][3];
                                ?>
                                <!--       <br/>
                                      <img  style="margin-top:5px;" src="images/kitafino_icon.png" /> <?php echo $projekt_id_db ?>     -->                      

                                <?php
                                if ($portionen_do != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
                                    $alert_kitafino_zahlen = 'border: 2px solid red;';
                                    $readonly = '';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <input style="<?php echo $alert_kitafino_zahlen ?>" class="portionen_input" type="text" name="portionen_do[]" <?php echo $readonly ?> value="<?php echo $portionen_do ?>" onblur="javascript: checkAnzahlPortionen(this)"  onkeyup="javascript: checkAnzahlPortionen(this)"/>
                        <?php
                        if ($portionen_do != $standardportionen->getPortionenDo()) {
                            echo '<sup>1</sup> (' . $standardportionen->getPortionenDo() . ')';
                        }
                        ?>
                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>"type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                        <?php } ?>
                    </td>

                    <td><?php echo $kunde->getName(); ?> <?php if ($kunde->getAnzahlSpeisen() > 1) { ?> - Speise 1<?php } ?>
                        <?php echo $echo_fr ?> 
                        <?php
                        if ($_REQUEST['dev']) {
                            $alert_kitafino_zahlen = '';
                            $readonly = '';
                            if ($kunde->getKundennummer()) {
                                $readonly = 'readonly';
                                $projekt_id_db = $kunde->getKundennummer();
                                // include 'functions/connect_db.php';
                                //  $count_orders = ermittleZahlenZuKundeTag($freitagts, $projekt_id_db);
                                $count_orders = $bestellzahlen[4];
                                $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][4];
                                ?>
                                <!--   <br/>
                                   <img  style="margin-top:5px;" src="images/kitafino_icon.png" /> <?php echo $projekt_id_db ?>  -->                      

                                <?php
                                if ($portionen_fr != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
                                    $alert_kitafino_zahlen = 'border: 2px solid red;';
                                    $readonly = '';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <input style="<?php echo $alert_kitafino_zahlen ?>" class="portionen_input" type="text" name="portionen_fr[]" <?php echo $readonly ?> value="<?php echo $portionen_fr ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                        <?php
                        if ($portionen_fr != $standardportionen->getPortionenFr()) {
                            echo '<sup>1</sup> (' . $standardportionen->getPortionenFr() . ')';
                        }
                        ?>
                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                        <?php } ?>
                    </td>

                    <td><input type="submit" value="" class="save_btn"/></td>
                </tr>