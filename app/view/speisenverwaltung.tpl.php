<form method="post" action="index.php?action=neue_speise_step2">
    <div class="form_box">
        <label class="form_label">Neue Speise</label>
        <input class="form_input" type="text" name="bezeichnung" value="" />
        <input type="submit" class="submit_btn" value="weiter" />
    </div>
</form>

<br />
<a class="action_links" href="index.php?action=erstelle_speisenuebersicht">Speisenübersicht als XLS</a>

<form method="post" action="index.php?action=save_speisen_settings&sort=<?php echo $_REQUEST['sort'] ?>">
    <table>
        <tr>
            <th style="width:200px;"><a href="index.php?action=speisenverwaltung&sort=bezeichnung">Speise</a></th>
            <th><a href="index.php?action=speisenverwaltung&sort">Nr</a></th>
            <th colspan="2">Aktion</th>
            <th colspan="">kaltverpackt</th>
            <th colspan="">Merkmale</th>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td><input type="submit" class="submit_btn" style="margin-left: 5px;width:70px;" value="speichern" title="speichert alle Änderungen"/></td>
        </tr>
        <?php
        $cs = 1;
        foreach ($speisen as $speise) {
            if ($cs % 10 == 0) {
                ?>
                <tr>
                    <td colspan="4"></td>
                    <td><input type="submit" class="submit_btn" style="margin-left: 5px;width:70px;" value="speichern" title="speichert alle Änderungen" /></td>
                </tr>

                <?php
            }
            $cs++;
            $fehler = array();
            for ($i = 0; $i <= count($fehler_in); $i++) {
                ?>
                <?php
                if (isset($fehler_in[$i][0])) {
                    if ($fehler_in[$i][0] == $speise->getId()) {
                        $fehler[] = true;
                    } else {
                        $fehler[] = false;
                    }
                }
            }
            ?>

            <tr>        
                <td <?php
                if (array_search(true, $fehler) !== FALSE) {
                    echo 'class="speise_fehler_td"';
                }
                ?>><?php echo $speise->getBezeichnung() ?></td>
                <td <?php
                if (array_search(true, $fehler) !== FALSE) {
                    echo 'class="speise_fehler_td"';
                }
                ?>><?php echo $speise->getId() ?></td>
                <td style="line-height: 23px;">
                    <a  class="action_links action_links_btns" href="index.php?action=speise_bearbeiten&speise_id=<?php echo $speise->getId() ?>">Bearbeiten</a>
                    <a class="action_links action_links_btns" href="index.php?action=real_delete&what=speise&id=<?php echo $speise->getId() ?>">löschen</a>
                </td>
                <td>
                    <a class="action_links action_links_btns" href="index.php?action=speise_menge_festlegen&speise_id=<?php echo $speise->getId() ?>">Mengen definieren</a>
                    <?php if ($speise->getRezept() != '') { ?>
                        <a class="action_links action_links_btns" style="color: green;" href="index.php?action=rezept&speise_id=<?php echo $speise->getId() ?>">Rezept/Bemerk. bearbeiten</a>
                    <?php } else { ?>
                        <a class="action_links action_links_btns" href="index.php?action=rezept&speise_id=<?php echo $speise->getId() ?>">Rezept/Bemerk. hinterlegen</a>
                    <?php } ?>
                    <a class="action_links action_links_btns" href="index.php?action=kopiere_speise&speise_id=<?php echo $speise->getId() ?>">duplizieren</a>

                    <?php if (array_search(true, $fehler) !== FALSE) { ?>(fehlende Mengenangaben!) <?php } ?>
                </td>
                <td><input <?php
                    if ($speise->getKaltVerpackt()) {
                        echo 'checked="checked"';
                    }
                    ?> type="checkbox" style="width:25px;height:25px;" name="kalt_verpackt_speisen_ids[]" value="<?php echo $speise->getId() ?>" /></td>
                <td>
                    <div id="loader_<?php echo $speise->getId() ?>">

                    </div>

                    <div id="loader_merkmal_bio_<?php echo $speise->getId() ?>"> 
                        <img class="loader" src="images/load.gif" style="display:none;">
                        <label title="Bio Speise" for="set_bio_<?php echo $speise->getId() ?>" id="label_set_bio_<?php echo $speise->getId() ?>">
                            <input type="checkbox" id="set_bio_<?php echo $speise->getId() ?>" name="bio[<?php echo $speise->getId() ?>]" value="<?php echo $speise->getBio() ?>" <?php
                            if ($speise->getBio()) {
                                echo 'checked';
                            }
                            ?>
                                   class="mark_bio" data-speise-id="<?php echo $speise->getId() ?>"
                                   />
                                   <?php
                                   if ($speise->getBio()) {
                                       $opacity = 1;
                                   } else {
                                       $opacity = 0.3;
                                   }
                                   ?>
                            <img id="bioimg_<?php echo $speise->getId() ?>" style="margin-top:5px;max-width: 30px;opacity: <?php echo $opacity ?>;" src="../images/biosiegel_small.jpg" />
                        </label>
                    </div>

                    <div id="loader_merkmal_cooled_<?php echo $speise->getId() ?>"> 
                        <img class="loader" src="images/load.gif" style="display:none;">
                        <label title="wird gekühlt geliefert" for="set_cooled_<?php echo $speise->getId() ?>" id="label_set_cooled_<?php echo $speise->getId() ?>">
                            <input type="checkbox" id="set_cooled_<?php echo $speise->getId() ?>" name="cooled[<?php echo $speise->getId() ?>]" value="<?php echo $speise->getCooled() ?>" <?php
                            if ($speise->getCooled()) {
                                echo 'checked';
                            }
                            ?>
                                   class="mark_cooled" data-speise-id="<?php echo $speise->getId() ?>"
                                   />
                                   <?php
                                   if ($speise->getCooled()) {
                                       $opacity = 1;
                                   } else {
                                       $opacity = 0.3;
                                   }
                                   ?>
                            <img id="cooledimg_<?php echo $speise->getId() ?>" style="margin-top:5px;max-width: 30px;opacity: <?php echo $opacity ?>;" src="images/cooled.png" />
                        </label>
                    </div>
                    
                    
                    <div id="loader_merkmal_nonprint_<?php echo $speise->getId() ?>"> 
                        <img class="loader" src="images/load.gif" style="display:none;">
                        <label title="nicht in Lieferschein andrucken" for="set_nonprint_<?php echo $speise->getId() ?>" id="label_set_nonprint_<?php echo $speise->getId() ?>">
                            <input type="checkbox" id="set_nonprint_<?php echo $speise->getId() ?>" name="nonprint[<?php echo $speise->getId() ?>]" value="<?php echo $speise->getNonprint() ?>" <?php
                            if ($speise->getNonprint()) {
                                echo 'checked';
                            }
                            ?>
                                   class="mark_nonprint" data-speise-id="<?php echo $speise->getId() ?>"
                                   />
                                   <?php
                                   if ($speise->getNonprint()) {
                                       $opacity = 1;
                                   } else {
                                       $opacity = 0.3;
                                   }
                                   ?>
                            <img id="nonprintimg_<?php echo $speise->getId() ?>" style="margin-top:5px;max-width: 30px;opacity: <?php echo $opacity ?>;" src="images/nonprint.png" />
                        </label>
                    </div>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="5"></td>
            <td><input type="submit" class="submit_btn" style="margin-left: 5px;width:74px;" value="speichern" title="speichert alle Änderungen" /></td>
        </tr>
    </table>

</form>

<script>


    $(document).ready(function () {

        $('.mark_bio').on('change', function () {

            saveData($(this));
        });

        $('.mark_cooled').on('change', function () {

            saveDataCooled($(this));
        });
        $('.mark_nonprint').on('change', function () {

            saveDataNonprint($(this));
        });
        
        function saveDataNonprint(el) {

            var set_nonprint_bool = el.prop('checked');
            var speise_id = el.data('speise-id');
            if (set_nonprint_bool) {
                set_nonprint = 1;
                $('#nonprintimg_' + speise_id).css('opacity', 1);
            } else {
                set_nonprint = 0;
                $('#nonprintimg_' + speise_id).css('opacity', 0.3);
            }
            //$('#loader_' + speise_id).html('<img src="images/load.gif">');
            $('#loader_merkmal_nonprint_' + speise_id + ' .loader').fadeIn();
            $('#label_set_nonprint_' + speise_id).hide();
        
    $.ajax({
                url: 'includes/save_speisemerkmal.php',
                type: 'post',
                data: {set_nonprint: set_nonprint, speise_id: speise_id},
                success: function (response) {
                    setTimeout(function () {
                        //   $('#'+loader).html('<img src="images/load.gif">');
                        //  $('#' + loader).html('<img src="images/saved_icon.png">').delay(2000);
                        $('#loader_merkmal_nonprint_' + speise_id + ' .loader').fadeOut();
                        $('#label_set_nonprint_' + speise_id).delay(400).fadeIn();

                    }, 500);
                }
            });
        }
        
        function saveDataCooled(el) {

            var set_cooled_bool = el.prop('checked');
            var speise_id = el.data('speise-id');
            if (set_cooled_bool) {
                set_cooled = 1;
                $('#cooledimg_' + speise_id).css('opacity', 1);
            } else {
                set_cooled = 0;
                $('#cooledimg_' + speise_id).css('opacity', 0.3);
            }
            //$('#loader_' + speise_id).html('<img src="images/load.gif">');
            $('#loader_merkmal_cooled_' + speise_id + ' .loader').fadeIn();
            $('#label_set_cooled_' + speise_id).hide();
            /* 
             $('#portionen_in_db_' + focus_nr).hide();
             $('#standardportionen_' + focus_nr).hide();
             $('#' + loader).html('<img src="images/load.gif">');
             
             // $('#' + loader).show();
             */
            $.ajax({
                url: 'includes/save_speisemerkmal.php',
                type: 'post',
                data: {set_cooled: set_cooled, speise_id: speise_id},
                success: function (response) {
                    setTimeout(function () {
                        //   $('#'+loader).html('<img src="images/load.gif">');
                        //  $('#' + loader).html('<img src="images/saved_icon.png">').delay(2000);
                        $('#loader_merkmal_cooled_' + speise_id + ' .loader').fadeOut();
                        $('#label_set_cooled_' + speise_id).delay(400).fadeIn();

                    }, 500);
                }
            });
        }
        function saveData(el) {

            var set_bio_bool = el.prop('checked');
            var speise_id = el.data('speise-id');
            if (set_bio_bool) {
                set_bio = 1;
                $('#bioimg_' + speise_id).css('opacity', 1);
            } else {
                set_bio = 0;
                $('#bioimg_' + speise_id).css('opacity', 0.3);
            }
            $('#loader_merkmal_bio_' + speise_id + ' .loader').fadeIn();
            $('#label_set_bio_' + speise_id).hide();
            /* 
             $('#portionen_in_db_' + focus_nr).hide();
             $('#standardportionen_' + focus_nr).hide();
             $('#' + loader).html('<img src="images/load.gif">');
             
             // $('#' + loader).show();
             */
            $.ajax({
                url: 'includes/save_speisemerkmal.php',
                type: 'post',
                data: {set_bio: set_bio, speise_id: speise_id},
                success: function (response) {
                    setTimeout(function () {
                        //   $('#'+loader).html('<img src="images/load.gif">');
                        //  $('#' + loader).html('<img src="images/saved_icon.png">').delay(2000);
                        $('#loader_merkmal_bio_' + speise_id + ' .loader').fadeOut();
                        $('#label_set_bio_' + speise_id).delay(400).fadeIn();

                    }, 500);
                }
            });
        }
    });
</script>

