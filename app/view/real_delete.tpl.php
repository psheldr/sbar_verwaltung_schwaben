<p>
    Wirklich <?php echo $to_delete_string ?> löschen?
</p>

<?php echo $hinweis ?>
<?php
if ($hinweis != '') { ?>
<ul style="margin: 10px 10px 10px 30px;">
        <?php
        foreach($kunden_mit_kat as $kunde) {
            ?>
    <li><?php echo $kunde->getName() ?></li>
        <?php } ?>
</ul>
<?php } else { ?>
<a class="action_links" href="index.php?action=do_delete&what=<?php echo $what ?>&id=<?php echo $to_delete_obj->getId() ?>">JA</a>
<a class="action_links" href="index.php?action=<?php echo $abort_action ?>">NEIN</a>
<?php } ?>