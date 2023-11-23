
<table class="navi_tbl" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <a href="index.php?action=cockpit" <?php if($action == 'cockpit') {echo 'class="active_navi"';} ?>>Kundensuche</a>
        </td>
        <td>
            <a href="index.php?action=speiseplaene" <?php if($action == 'speiseplaene') {echo 'class="active_navi"';} ?>>Wochenübersicht</a>
        </td>
        <td>
            <a href="index.php?action=speisenwoche" <?php if($action == 'speisenwoche') {echo 'class="active_navi"';} ?>>Speisenübersicht</a>
        </td>
        <?php if ($_SESSION['logged_in_recht'] != 3) { ?>
        <td>
            <a href="index.php?action=lieferorganisation" <?php if($action == 'lieferorganisation') {echo 'class="active_navi"';} ?>>Lieferorganisation</a>
        </td>
         <?php } ?>
        <?php if ($_SESSION['logged_in_recht'] != 3) { ?>
        <td>
            <a href="index.php?action=produktionsorganisation" <?php if($action == 'produktionsorganisation') {echo 'class="active_navi"';} ?>>Produktionsorganisation</a>
        </td>
         <?php } ?>
        <?php if ($_SESSION['logged_in_recht'] == 1 || $_SESSION['logged_in_user_id'] == 4 ) { ?>
        <td>
            <a href="index.php?action=kundenverwaltung" <?php if($action == 'kundenverwaltung' || $action == 'neuer_kunde') {echo 'class="active_navi"';} ?>>Kundenverwaltung</a>
        </td>
         <?php } ?>
        <?php if ($_SESSION['logged_in_recht'] != 3) { ?>
        <td>
            <a href="index.php?action=einrichtungsverwaltung" <?php if($action == 'einrichtungsverwaltung') {echo 'class="active_navi"';} ?>>Kategorieverwaltung</a>
        </td>
       
        <td>
            <a href="index.php?action=speisenverwaltung" <?php if($action == 'speisenverwaltung') {echo 'class="active_navi"';} ?>>Speisenverwaltung</a>
        </td>
        <?php } ?>

    </tr>
        <?php if ($_SESSION['logged_in_recht'] == 1) { ?>
    <tr>
        <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
        <td>            
            <a href="index.php?action=etiketten">Besteck Etiketten</a>
        </td>
        <td>            
            <a href="index.php?action=fahrer_codes">Fahrer-Codes verwalten</a>
        </td>
        <td>            
            <a href="../sbarscan/index.php?action=barcodes">Fahrer-/Tourcodes anzeigen</a>
        </td>
        <td>
            <a href="index.php?action=infoticker" <?php if($action == 'infoticker') {echo 'class="active_navi"';} ?>>Tagesinfos</a>
        </td>
        
        <?php if ($_SESSION['logged_in_recht'] == 1) { ?>
        <td>
            <a href="index.php?action=abrechnung" <?php if($action == 'abrechnung') {echo 'class="active_navi"';} ?>>Abrechnung</a>
        </td>
        <?php } ?>
        <?php if ($_SESSION['logged_in_recht'] == 1) { ?>
        <td>
            <a href="index.php?action=abrechnung_stadt_nbg" <?php if($action == 'abrechnung_stadt_nbg') {echo 'class="active_navi"';} ?>><img style="width:15px;" src="images/nbg_icon.png"/> Abrechnung Stadt</a>
        </td>
        <?php } ?>
    </tr>
        <?php } ?>
</table>
<?php
$user_logged_in = $userVerwaltung->findeAnhandVonId($_SESSION['logged_in_user_id']);
?>

<p class="user_nav" >Angemeldet als <strong><?php echo $user_logged_in->getUsername() ?></strong> (letzter Login: <?php echo strftime('%d.%m.%Y. - %H:%M Uhr', $user_logged_in->getLetzterLogin()) ?>)
    &nbsp;&nbsp;
    <a  href="index.php?action=change_pw">Passwort ändern</a>
&nbsp;&nbsp;<a  href="index.php?action=log_out" <?php if($action == 'log_out') {echo 'class="active_navi"';} ?>>abmelden >></a>
</p>