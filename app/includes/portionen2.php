
                    <tr  class="<?php echo $highlight_class ?>">

                        <td style="border-bottom: 0px solid #888;">
                        <input type="hidden" name="portionenaenderung2_id[]" value="<?php echo $portionenaenderung2->getId() ?>" />
<?php echo $rowcount; ?>                         
   <?php echo $kunde->getName(); ?> - Speise 2

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

                            <input class="portionen_input" type="text" name="portionen2_mo[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen2_mo ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen2_mo != $standardportionen2->getPortionenMo()) {
                                echo '<sup>1</sup> (' . $standardportionen2->getPortionenMo() . ')';
                            }
                            ?>

                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][2][0];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>

                        </td>

                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 2
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen2_di[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen2_di ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen2_di != $standardportionen2->getPortionenDi()) {
                                echo '<sup>1</sup> (' . $standardportionen2->getPortionenDi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][2][1];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 2
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen2_mi[<?php echo $rowcount - 2 ?>]"  <?php echo $readonly ?> value="<?php echo $portionen2_mi ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen2_mi != $standardportionen2->getPortionenMi()) {
                                echo '<sup>1</sup> (' . $standardportionen2->getPortionenMi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][2][2];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 2
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen2_do[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen2_do ?>" onblur="javascript: checkAnzahlPortionen(this)"  onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen2_do != $standardportionen2->getPortionenDo()) {
                                echo '<sup>1</sup> (' . $standardportionen2->getPortionenDo() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][2][3];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 2
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen2_fr[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen2_fr ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen2_fr != $standardportionen2->getPortionenFr()) {
                                echo '<sup>1</sup> (' . $standardportionen2->getPortionenFr() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][2][4];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>



                    </tr>