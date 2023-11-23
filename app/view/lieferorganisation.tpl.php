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
    #list li {
        margin: 0 0 3px;
        padding:8px;
        background-color: #EC5006;
        color:#fff;
        list-style: none;
    }
    #list .trenner {
        margin: 0 0 3px;
        padding:8px;
        background-color: #aaaaaa;
        color:#fff;
        list-style: none;
    }
    .anchors  {
        display: block;
        position: relative;
        top: -150px;
        visibility: hidden;
    }
</style>

<div id="container">
    <div id="list">
        <div id="response"> </div>
        <ul>
            <?php
            include("connect.php");
            $query = "SELECT id, name, essenszeit, lieferreihenfolge, einrichtungskategorie_id, staedtischer_kunde, bio_kunde, tour_id FROM kunde WHERE aktiv = 1 ORDER BY lieferreihenfolge ASC";
            $result = mysqli_query($conn,$query);
			$active_tour_id = 0;
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = stripslashes($row['id']);
                $text = stripslashes($row['name']);
                $essenszeit = stripslashes($row['essenszeit']);
                $essenszeit = substr($essenszeit, 0, 2) . ':' . substr($essenszeit, 2, 2) . ' Uhr';
                $lieferreihenfolge = stripslashes($row['lieferreihenfolge']);
                $kat_id = $row['einrichtungskategorie_id'];
                $tour_id_show = $row['tour_id'];
                ?>
                <?php if ($row['einrichtungskategorie_id'] == '5') { 
				
				if ($row['id'] !== $active_tour_id) {
					$active_tour_id = $row['id'];
				}
				?>
                    <li class="trenner" id="arrayorder_<?php echo $id ?>" data-kat="<?php echo $kat_id ?>" data-tourid="<?php echo $tour_id_show ?>" data-movedid="<?php echo $id ?>">
                        <a class="anchors" name="anchor_<?php echo $id ?>"></a>                 
                        <?php echo $lieferreihenfolge ?>
                        <?php echo $text; ?>
                        <span style="float: right;">[verschieben um ganze Tour zu bewegen - vor anderer Tour platzieren]</span>
                        <?php if($_SESSION['logged_in_user_id'] == 5) {
							echo $id;
							echo ' tour:';
							echo $tour_id_show ;
							echo ' LR:';
							echo $active_tour_id ;
						} ?>
                        <?php  ?>
                    </li>
                <?php } else { 
					$style_warn = '';
				if ($tour_id_show != $active_tour_id) {
					$style_warn = 'style="background-color: yellow;color:#000;"';
				}
				?>
                    <li class="einrichtung_item " <?php echo $style_warn ?> id="arrayorder_<?php echo $id ?>" data-kat="<?php echo $kat_id ?>" data-tourid="<?php echo $tour_id_show ?>" data-movedid="<?php echo $id ?>">
                        <a class="anchors" name="anchor_<?php echo $id ?>"></a>
                        <?php echo $lieferreihenfolge ?>
                        <strong><?php echo $text; ?></strong> -
                        Essenszeit:
                        <?php if ($_REQUEST['do'] == 'edit_essenszeit' && $_REQUEST['kid'] == $id) { ?>
                            <form method="post" action="index.php?action=lieferorganisation&do=save_edit_essenszeit">
                                <input type="text" name="essenszeit_h" value="<?php echo substr($essenszeit, 0, 2) ?>" />
                                <input type="text" name="essenszeit_m" value="<?php echo substr($essenszeit, 3, 2) ?>" />
                                <input type="hidden" name="id" value="<?php echo $id ?>" />
                                <input type="submit" />
                            </form>
                        <?php } else { ?>
                            <?php echo $essenszeit ?>
                            <a href="index.php?action=lieferorganisation&do=edit_essenszeit&kid=<?php echo $id ?>" title="Essenszeit bearbeiten"><img src="images/edit_btn.jpg" /></a>
                        <?php } ?>

                        <?php if ($row['bio_kunde']) { ?>
                            <img src="../images/biosiegel_small.jpg"  style="width:25px;float: right;" />
                        <?php } ?>
                        <?php if ($row['staedtischer_kunde']) { ?>
                            <img src="images/nbg_icon.png" style="width:20px;float: right;" />
                        <?php } ?>

                        <?php //echo $id ?>
                        <?php //echo $tour_id_show ?>
 <?php if($_SESSION['logged_in_user_id'] == 5) {
							echo $id;
							echo ' tour:';
							echo $tour_id_show ;
							echo ' LR:';
							echo $active_tour_id ;
						} ?>
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