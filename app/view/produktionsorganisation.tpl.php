<style>
    ul {
        padding:0px;
        margin: 0px;
    }
    #response {
        padding:10px;
        background-color:#9F9;
        border:2px solid #396;
        margin-bottom:20px;
    }
    #list_prod li {
        margin: 0 0 3px;
        padding:8px;
        background-color: #EC5006;
        color:#fff;
        list-style: none;
    }
    #list_prod .trenner {
        margin: 0 0 3px;
        padding:8px;
        background-color: #aaaaaa;
        color:#fff;
        list-style: none;
    }
</style>
<a href="index.php?action=produktionsorganisation&getliefer=1">Reihenfolge von Lieferreihenfolge übertragen</a><br /><br />
<?php
?>
<div id="container">
    <div id="list_prod">
        <div id="response"> </div>
        <ul>
            <?php
            include("connect.php");

            if ($_REQUEST['getliefer']) {
                $query = "SELECT id, name, essenszeit, lieferreihenfolge, einrichtungskategorie_id, produktionsreihenfolge FROM kunde WHERE aktiv = 1 AND einrichtungskategorie_id IN (5) ORDER BY lieferreihenfolge ASC";
            } else {
                $query = "SELECT id, name, essenszeit, lieferreihenfolge, einrichtungskategorie_id, produktionsreihenfolge FROM kunde WHERE aktiv = 1 AND einrichtungskategorie_id IN (5) ORDER BY produktionsreihenfolge ASC";
            }


            $result = mysqli_query($conn,$query);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = stripslashes($row['id']);
                $text = stripslashes($row['name']);
                $essenszeit = stripslashes($row['essenszeit']);
                $essenszeit = substr($essenszeit, 0, 2) . ':' . substr($essenszeit, 2, 2) . ' Uhr';
                $lieferreihenfolge = stripslashes($row['lieferreihenfolge']);
                $produktionsreihenfolge = stripslashes($row['produktionsreihenfolge']);
                /*  echo '<pre>';
                  var_dump($query,$row);
                  echo '</pre>'; */
                ?>

                <?php if ($row['einrichtungskategorie_id'] == '5') { ?>
                    <li class="trenner" id="arrayorder_<?php echo $id ?>">
                        <?php echo $produktionsreihenfolge ?>
                        <?php echo $text; ?>
                    </li>
                <?php } else { ?>
                    <li id="arrayorder_<?php echo $id ?>">
                        <?php echo $produktionsreihenfolge ?>
                        <strong><?php echo $text; ?></strong> -
                        Essenszeit:
                        <?php if ($_REQUEST['do'] == 'edit_essenszeit' && $_REQUEST['kid'] == $id) { ?>
                            <form method="post" action="index.php?action=produktionsorganisation&do=save_edit_essenszeit">
                                <input type="text" name="essenszeit_h" value="<?php echo substr($essenszeit, 0, 2) ?>" />
                                <input type="text" name="essenszeit_m" value="<?php echo substr($essenszeit, 3, 2) ?>" />
                                <input type="hidden" name="id" value="<?php echo $id ?>" />
                                <input type="submit" />
                            </form>
                        <?php } else { ?>
                            <?php echo $essenszeit ?>
                            <a href="index.php?action=produktionsorganisation&do=edit_essenszeit&kid=<?php echo $id ?>" title="Essenszeit bearbeiten"><img src="images/edit_btn.jpg" /></a>
                        <?php } ?>
                        <div class="clear"></div>
                    </li>
                <?php } ?>

            <?php } ?>
        </ul>
    </div>
</div>
<p>
    Mit Drag and Drop verschieben und die Reihenfolge ändern!
</p>
<?php if ($_REQUEST['getliefer']) { ?>
    <script>
        $("#list_prod ul").sortable();
        $(document).ready(
                function () {
                    var order = $("#list_prod ul").sortable("serialize") + '&update=update';
                    $.post("updateList_prod.php", order, function (theResponse) {
                        window.location.href = 'index.php?action=produktionsorganisation';
                    });
                });
    </script>
<?php } ?>