<?php
if (isset($_REQUEST['tag'])) {
    $tag = $_REQUEST['tag'];
} else {
    $tag = date('d');
}
if (isset($_REQUEST['monat'])) {
    $monat = $_REQUEST['monat'];
} else {
    $monat = date('m');
}
if (isset($_REQUEST['jahr'])) {
    $jahr = $_REQUEST['jahr'];
} else {
    $jahr = date('Y');
}
$tag = 1*$tag;
$monat = 1*$monat;

$anzahl_tage_monat = date('t', mktime(12,0,0,$monat,1,date('Y')));


?>
<h4>Tagesinformation speichern für</h4>
<p>

</p>
<form method="post" action="index.php?action=speichere_infoticker" id="infoticker_form">
    <select name="tag">
        <?php for ($c = 1; $c <= $anzahl_tage_monat; $c++) {
            $tag_str_eng = date('D', mktime(12,0,0,$monat,$c,$jahr));
            switch($tag_str_eng) {
                case 'Mon':
                    $tag_str = 'Mo';
                    break;
                case 'Tue':
                    $tag_str = 'Di';
                    break;
                case 'Wed':
                    $tag_str = 'Mi';
                    break;
                case 'Thu':
                    $tag_str = 'Do';
                    break;
                case 'Fri':
                    $tag_str = 'Fr';
                    break;
                case 'Sat':
                    $tag_str = 'Sa';
                    break;
                case 'Sun':
                    $tag_str = 'So';
                    break;
            }
            ?>
        <option <?php if($c == $tag) {echo 'selected="selected"';} ?> value="<?php echo $c ?>"><?php echo $tag_str ?>, <?php echo $c ?></option>
        <?php } ?>
    </select>
    <select name="monat">
        <?php for ($i = 1; $i <= 12; $i++) { ?>
        <option <?php if($i == $monat) {echo 'selected="selected"';} ?> value="<?php echo $i ?>"><?php echo $i ?></option>
        <?php } ?>
    </select>
    <select name="jahr">
        <?php for($d = date('Y'); $d <= date('Y')+1; $d++) { ?>
        <option <?php if($d == $jahr) {echo 'selected="selected"';} ?> value="<?php echo $d ?>"><?php echo $d ?></option>
        <?php } ?>
    </select>
    <br />
    <br />
    <textarea cols="80" rows="10" name="text"></textarea>
    <input type="hidden" name="eingetragen" value="<?php echo time() ?>" />
    <input type="hidden" name="erledigt" value="0" />
    <input class="submit_btn" type="submit" value="speichern" />
</form>
<p>
    Zum Löschen den entsprechenden Eintrag anklicken:
</p>
<?php
foreach ($infos as $info) {
     switch(date('D', $info->getDatumTs())) {
                case 'Mon':
                    $tag_str2 = 'Mo';
                    break;
                case 'Tue':
                    $tag_str2 = 'Di';
                    break;
                case 'Wed':
                    $tag_str2 = 'Mi';
                    break;
                case 'Thu':
                    $tag_str2 = 'Do';
                    break;
                case 'Fri':
                    $tag_str2 = 'Fr';
                    break;
                case 'Sat':
                    $tag_str2 = 'Sa';
                    break;
                case 'Sun':
                    $tag_str2 = 'So';
                    break;
            }

    ?>
<a href="index.php?action=infoticker_done&id=<?php echo $info->getId() ?>" class="infoticker_link" onclick="return confirm('Soll dieser Hinweis wirklich gelöscht werden?')">
    <span class="datum">
        <?php echo $tag_str2 . ' ' . strftime('%d.%m.%Y', $info->getDatumTs()) ?>
        <span class="datum_zusatz">
            (eingetragen am: <?php echo strftime('%d.%m.%Y - %H:%M Uhr', $info->getEingetragen()) ?>)
        </span>
    </span>
    <span class="text">
        <?php echo $info->getText() ?>
    </span>
</a>
<?php
}
?>