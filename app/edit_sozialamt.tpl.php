<?php
?>

<p class="<?php echo $_SESSION['hinweis_class'] ?>">
<?php
echo $_SESSION['hinweis'];
unset($_SESSION['hinweis'])
?>
</p>

<form method="post" action="index.php?action=save_sozialamt&do=1">
    <div id="cont_box_left">
        <h6>

<?php if ($neues_amt->getId()) { ?>
                Sozialträger <strong><?php echo $neues_amt->getBezeichnung() ?></strong> bearbeiten
            <?php } else { ?>            
                Neuer Sozialträger
            <?php } ?>
        </h6>

        <p class="error_text">
<?php if (!$neues_amt->istValide()) { ?>
                Bitte prüfen Sie folgende Pflichtfelder:<br />
                <?php
                echo $neues_amt->zeigeFehler();
            }
            ?>
        </p>
        <div>
            <label class="form_label" for="">Bezeichnung*
                <input class="form_input" type="text" name="bezeichnung"  value="<?php echo $neues_amt->getBezeichnung() ?>" /></label>
        </div><br />
     

      

        <label class="form_label" for="" width="">Notiz</label>
        <textarea class="" name="notiz" rows="6" ><?php echo br2nl($neues_amt->getNotiz()) ?></textarea><br />
<br />
        <label class="form_label" for="">Ansprechpartner</label>
        <input class="form_input" type="text" name="asp" value="<?php echo $neues_amt->getAsp() ?>" />
<br />
        <label class="form_label" for="">Straße*</label>
        <input class="form_input" type="text" name="strasse" value="<?php echo $neues_amt->getStrasse() ?>" />
<br />
        <div class="column small-5">
            <label class="form_label" id="label_plz" for="">PLZ*</label>

            <input class="form_input" type="text" id="input_plz" name="plz"  value="<?php if($neues_amt->getPlz()==0){echo '';}else{echo $neues_amt->getPlz();} ?>" />
        </div><br />
        <div class="column small-15">
            <label class="form_label" id="label_ort" for="">Ort*</label>

            <input class="form_input" id="input_ort" type="text" name="ort"  value="<?php echo $neues_amt->getOrt() ?>" />
        </div><br />



        <label class="form_label" for="">Telefon</label>
        <input class="form_input" type="text" name="tel" value="<?php echo $neues_amt->getTel() ?>" />
<br />
        <label class="form_label" for="">Telefon 2</label>
        <input class="form_input" type="text" name="tel2" value="<?php echo $neues_amt->getTel2() ?>" />
<br />
        <label class="form_label" for="">Fax</label>
        <input class="form_input" type="text" name="fax" value="<?php echo $neues_amt->getFax() ?>" />
<br />
    <label class="form_label" for="">E-Mail-Adresse 1 <!-- <span class="hochgestellte_fussnote">1)</span>--></label>
        <input class="form_input" type="text" name="email" value="<?php echo $neues_amt->getEmail() ?>" />
<br />
    <label class="form_label" for="">E-Mail-Adresse 2 <!-- <span class="hochgestellte_fussnote">1)</span>--></label>
        <input class="form_input" type="text" name="email2" value="<?php echo $neues_amt->getEmail2() ?>" />
<br />
<label class="form_label" for="">E-Mail-Adresse 3 <!-- <span class="hochgestellte_fussnote">1)</span>--></label>
        <input class="form_input" type="text" name="email3" value="<?php echo $neues_amt->getEmail3() ?>" />
<br />


        <input type="hidden" name="aktiv" value="1" />
        <input type="hidden" name="id" value="<?php echo $neues_amt->getId() ?>" />
        <input type="submit" class="button success" value="Abschicken"/>
        <p class="info_txt">*Pflichtfelder</p>
    </div>

</form>