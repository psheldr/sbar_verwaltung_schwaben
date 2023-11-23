
                    <tr  class="<?php echo $highlight_class ?>">

                        <td style="border-bottom: 0px solid #888;">
                        <input type="hidden" name="portionenaenderung4_id[]" value="<?php echo $portionenaenderung4->getId() ?>" />
<?php echo $rowcount; ?>                       
     <?php echo $kunde->getName(); ?> - Speise 4

                            <?php
                            if ($kunde->getKundennummer()) {
                                ?>
                                <br/>
                                <img style="margin-top:5px;" src="images/kitafino_icon.png" /> <strong><?php echo $projekt_id_db ?></strong>
                            <?php } ?>

                            <?php if ($kunde->getStaedtischerKunde()) { ?>
                                <br/>
                                <img style="margin-top:5px;" src="images/nbg_icon.png" />

                            <?php } ?>

                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen4_mo[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen4_mo ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen4_mo != $standardportionen4->getPortionenMo()) {
                                echo '<sup>1</sup> (' . $standardportionen4->getPortionenMo() . ')';
                            }
                            ?>

                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][4][0];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>

                        </td>

                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 4
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen4_di[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen4_di ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen4_di != $standardportionen4->getPortionenDi()) {
                                echo '<sup>1</sup> (' . $standardportionen4->getPortionenDi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][4][1];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 4
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen4_mi[<?php echo $rowcount - 2 ?>]"  <?php echo $readonly ?> value="<?php echo $portionen4_mi ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen4_mi != $standardportionen4->getPortionenMi()) {
                                echo '<sup>1</sup> (' . $standardportionen4->getPortionenMi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][4][2];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 4
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen4_do[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen4_do ?>" onblur="javascript: checkAnzahlPortionen(this)"  onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen4_do != $standardportionen4->getPortionenDo()) {
                                echo '<sup>1</sup> (' . $standardportionen4->getPortionenDo() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][4][3];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 4
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen4_fr[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen4_fr ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen4_fr != $standardportionen4->getPortionenFr()) {
                                echo '<sup>1</sup> (' . $standardportionen4->getPortionenFr() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][4][4];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>



                    </tr>