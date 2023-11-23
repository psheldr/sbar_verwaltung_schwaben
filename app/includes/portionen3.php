
                    <tr  class="<?php echo $highlight_class ?>">

                        <td style="border-bottom: 0px solid #888;">
                        <input type="hidden" name="portionenaenderung3_id[]" value="<?php echo $portionenaenderung3->getId() ?>" />
<?php echo $rowcount; ?>                        
    <?php echo $kunde->getName(); ?> - Speise 3

                            <?php
                            if ($kunde->getKundennummer()) {
                                ?>
                                <br/>
                                <img style="margin-top:5px;" src="images/kitafino_icon.png" /> <strong><?php echo $projekt_id_db ?></strong>
                            <?php } ?>

                            <?php if ($kunde->getStaedtischerKunde()) { ?>
                                <br/>
                                <img style="margin-top:5px;" src="images/nbg_icon.png" />
                            <img style="margin-top:5px;max-width: 30px;" src="../images/biosiegel_small.jpg" />

                            <?php } ?>
                        <?php if ($kunde->getBioKunde()) { ?>
                                <br/>
                           
                            <img style="margin-top:5px;max-width: 30px;" src="../images/biosiegel_small.jpg" />

                        <?php } ?>

                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen3_mo[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen3_mo ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen3_mo != $standardportionen3->getPortionenMo()) {
                                echo '<sup>1</sup> (' . $standardportionen3->getPortionenMo() . ')';
                            }
                            ?>

                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][3][0];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>

                        </td>

                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 3
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen3_di[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen3_di ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen3_di != $standardportionen3->getPortionenDi()) {
                                echo '<sup>1</sup> (' . $standardportionen3->getPortionenDi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][3][1];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 3
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen3_mi[<?php echo $rowcount - 2 ?>]"  <?php echo $readonly ?> value="<?php echo $portionen3_mi ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen3_mi != $standardportionen3->getPortionenMi()) {
                                echo '<sup>1</sup> (' . $standardportionen3->getPortionenMi() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][3][2];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 3
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen3_do[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen3_do ?>" onblur="javascript: checkAnzahlPortionen(this)"  onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen3_do != $standardportionen3->getPortionenDo()) {
                                echo '<sup>1</sup> (' . $standardportionen3->getPortionenDo() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][3][3];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                        <td style="border-bottom: 0px solid #888;">
                            <?php echo $kunde->getName(); ?> - Speise 3
                        </td>
                        <td style="border-bottom: 0px solid #888;">

                            <input class="portionen_input" type="text" name="portionen3_fr[<?php echo $rowcount - 2 ?>]" <?php echo $readonly ?> value="<?php echo $portionen3_fr ?>" onblur="javascript: checkAnzahlPortionen(this)" onkeyup="javascript: checkAnzahlPortionen(this)"/>
                            <?php
                            if ($portionen3_fr != $standardportionen3->getPortionenFr()) {
                                echo '<sup>1</sup> (' . $standardportionen3->getPortionenFr() . ')';
                            }
                            ?>
                            <?php
                            if ($kunde->getKundennummer()) {
                                $count_orders = $portionen_tage[$kunde->getKundennummer()][$kunde->getId()][3][4];
                                ?>
                                <br />
                                <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                            <?php } ?>
                        </td>


                    <td><input type="submit" value="" class="save_btn"/></td>

                    </tr>