

<h3>weitere Angaben zu <?php echo $kunde->getName() ?></h3>
        <?php if ($_REQUEST['what'] == 'edit') { ?> 
        <?php include 'includes/kunde_navi.php' ?>
        <?php } ?>

<div class="form_box" style="width: 75%;">
    <h3>Anzahl angebotener Speisen</h3>
    <p>
        Aktuell: <?php echo $kunde->getAnzahlSpeisen() ?>
    </p>
    <form method="post" action="index.php?action=save_speisenzahl">
        <input type="hidden" name="kunde_id" value="<?php echo $kunde->getId() ?>" />
        <select name="anzahl_speisen">
            <?php for ($i = 1; $i <= 2; $i++) { ?>
                <option <?php
                if ($i == $kunde->getAnzahlSpeisen()) {
                    echo 'selected="selected"';
                }
                ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
        </select>
        <input type="submit" value="Neue Speisen Anzahl speichern" />
    </form>
</div>

<div class="form_box" style="width: 75%;">
    <h3>Anzahl der Boxen 
        <img src="images/box.png" class="" style="float: left; margin-right: 20px;" /></h3>
    <p>
        Aktuell: <?php echo $kunde->getAnzahlBoxen() ?>
    </p>
    <form method="post" action="index.php?action=save_boxenzahl">
        <input type="hidden" name="kunde_id" value="<?php echo $kunde->getId() ?>" />
        <select name="anzahl_boxen">
            <?php for ($i = 1; $i <= 8; $i++) { ?>
                <option <?php
                if ($i == $kunde->getAnzahlBoxen()) {
                    echo 'selected="selected"';
                }
                ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
        </select>
        <input type="submit" value="Boxen Anzahl speichern" />
    </form>
    <br />
</div>


<div class="form_box" style="width: 75%;">

    <form method="post" action="index.php?action=neuer_kunde_step2&step=2&kid=<?php echo $kunde->getId() ?>">
        <input type="hidden" name="kunde_id" value="<?php echo $kunde->getId() ?>" />


        <div <?php
        if ($kunde->getStaedtischerKunde() == 1 || $kunde->getBioKunde() == 1) {
            echo 'style="display: none;"';
        }
        ?>>
            <input type="hidden" name="id" value="<?php echo $standardportionen->getId() ?>" />
            <h3>Standard Portionen Speise 1</h3>
            <label class="form_label" for="">Montag</label>
            <input type="text" class="form_input" name="portionen_mo" value="<?php echo $standardportionen->getPortionenMo() ?>" id="" /><br />

            <label class="form_label" for="">Dienstag</label>
            <input type="text" class="form_input" name="portionen_di" value="<?php echo $standardportionen->getPortionenDi() ?>" id="" /><br />

            <label class="form_label" for="">Mittwoch</label>
            <input type="text" class="form_input" name="portionen_mi" value="<?php echo $standardportionen->getPortionenMi() ?>" id="" /><br />

            <label class="form_label" for="">Donnerstag</label>
            <input type="text" class="form_input" name="portionen_do" value="<?php echo $standardportionen->getPortionenDo() ?>" id="" /><br />

            <label class="form_label" for="">Freitag</label>
            <input type="text" class="form_input" name="portionen_fr" value="<?php echo $standardportionen->getPortionenFr() ?>" id="" /><br />
            <br />
        </div>


        <div <?php
        if ($kunde->getStaedtischerKunde() == 1 || $kunde->getBioKunde() == 1 || $kunde->getAnzahlSpeisen() == 1) {
            echo 'style="display: none;"';
        }
        ?>>
            <input type="hidden" name="id2" value="<?php echo $standardportionen2->getId() ?>" />
            <h3>Standard Portionen Speise 2</h3>
            <label class="form_label" for="">Montag</label>
            <input type="text" class="form_input" name="portionen2_mo" value="<?php echo $standardportionen2->getPortionenMo() ?>" id="" /><br />

            <label class="form_label" for="">Dienstag</label>
            <input type="text" class="form_input" name="portionen2_di" value="<?php echo $standardportionen2->getPortionenDi() ?>" id="" /><br />

            <label class="form_label" for="">Mittwoch</label>
            <input type="text" class="form_input" name="portionen2_mi" value="<?php echo $standardportionen2->getPortionenMi() ?>" id="" /><br />

            <label class="form_label" for="">Donnerstag</label>
            <input type="text" class="form_input" name="portionen2_do" value="<?php echo $standardportionen2->getPortionenDo() ?>" id="" /><br />

            <label class="form_label" for="">Freitag</label>
            <input type="text" class="form_input" name="portionen2_fr" value="<?php echo $standardportionen2->getPortionenFr() ?>" id="" /><br />
            <br />
        </div>


        <div <?php
        if ($kunde->getStaedtischerKunde() == 0 && $kunde->getBioKunde() == 0) {
            echo 'style="display: none;"';
        }
        ?>>
            <input type="hidden" name="id3" value="<?php echo $standardportionen3->getId() ?>" />
            <h3>Standard Portionen Speise 3</h3>
            <label class="form_label" for="">Montag</label>
            <input type="text" class="form_input" name="portionen3_mo" value="<?php echo $standardportionen3->getPortionenMo() ?>" id="" /><br />

            <label class="form_label" for="">Dienstag</label>
            <input type="text" class="form_input" name="portionen3_di" value="<?php echo $standardportionen3->getPortionenDi() ?>" id="" /><br />

            <label class="form_label" for="">Mittwoch</label>
            <input type="text" class="form_input" name="portionen3_mi" value="<?php echo $standardportionen3->getPortionenMi() ?>" id="" /><br />

            <label class="form_label" for="">Donnerstag</label>
            <input type="text" class="form_input" name="portionen3_do" value="<?php echo $standardportionen3->getPortionenDo() ?>" id="" /><br />

            <label class="form_label" for="">Freitag</label>
            <input type="text" class="form_input" name="portionen3_fr" value="<?php echo $standardportionen3->getPortionenFr() ?>" id="" /><br />
            <br />
        </div>

        <div <?php
            if (($kunde->getStaedtischerKunde() == 0 && $kunde->getBioKunde() == 0) || ($kunde->getBioKunde() == 1 && $kunde->getAnzahlSpeisen() == 1)) {
                echo 'style="display: none;"';
            }
        ?>>
            <input type="hidden" name="id4" value="<?php echo $standardportionen4->getId() ?>" />
            <h3>Standard Portionen Speise 4</h3>
            <label class="form_label" for="">Montag</label>
            <input type="text" class="form_input" name="portionen4_mo" value="<?php echo $standardportionen4->getPortionenMo() ?>" id="" /><br />

            <label class="form_label" for="">Dienstag</label>
            <input type="text" class="form_input" name="portionen4_di" value="<?php echo $standardportionen4->getPortionenDi() ?>" id="" /><br />

            <label class="form_label" for="">Mittwoch</label>
            <input type="text" class="form_input" name="portionen4_mi" value="<?php echo $standardportionen4->getPortionenMi() ?>" id="" /><br />

            <label class="form_label" for="">Donnerstag</label>
            <input type="text" class="form_input" name="portionen4_do" value="<?php echo $standardportionen4->getPortionenDo() ?>" id="" /><br />

            <label class="form_label" for="">Freitag</label>
            <input type="text" class="form_input" name="portionen4_fr" value="<?php echo $standardportionen4->getPortionenFr() ?>" id="" /><br />
            <br />
        </div>

        <input type="submit" value="weiter" />
    </form>

</div>
