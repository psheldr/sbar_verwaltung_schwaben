

        <?php if ($no_delete) { ?>
<p style="color:red;font-weight:bold;">Nur Ansicht</p>

        <?php } ?>
<?php
if (count($infos) > 0) {
    foreach ($infos as $info) {
        if ($info->getDatumTs() > mktime(23, 0, 0, date('m'), date('d'), date('Y'))) {
            $classes = 'infoticker_link infoticker_zukunft';
        } else {
            $classes = 'infoticker_link';
        }
        switch (date('D', $info->getDatumTs())) {
            case 'Mon':
                $wt_ger = 'MO';
                break;
            case 'Tue':
                $wt_ger = 'DI';
                break;
            case 'Wed':
                $wt_ger = 'MI';
                break;
            case 'Thu':
                $wt_ger = 'DO';
                break;
            case 'Fri':
                $wt_ger = 'FR';
                break;
            case 'Sat':
                $wt_ger = 'SA';
                break;
            Case 'Sun':
                $wt_ger = 'SO';
                break;
        }
        ?>
        <?php if ($no_delete) { ?>
            <div  class="<?php echo $classes ?>" >
                <span class="datum">
                    <?php echo $wt_ger . ' ' . strftime('%d.%m.%Y', $info->getDatumTs()) ?>
                    <span class="datum_zusatz">
                        (eingetragen am: <?php echo strftime('%d.%m.%Y - %H:%M Uhr', $info->getEingetragen()) ?>)
                    </span>
                </span>
                <span class="text">
                    <?php echo $info->getText() ?>
                </span>
            </div>

        <?php } else { ?>
            <a href="index.php?action=infoticker_done_anz&id=<?php echo $info->getId() ?>&itanzid=<?php echo $_REQUEST['itanzid'] ?>" class="<?php echo $classes ?>" onclick="return confirm('Soll dieser Hinweis wirklich gelöscht werden?')">
                <span class="datum">
                    <?php echo $wt_ger . ' ' . strftime('%d.%m.%Y', $info->getDatumTs()) ?>
                    <span class="datum_zusatz">
                        (eingetragen am: <?php echo strftime('%d.%m.%Y - %H:%M Uhr', $info->getEingetragen()) ?>)
                    </span>
                </span>
                <span class="text">
                    <?php echo $info->getText() ?>
                </span>
            </a>
        <?php } ?>
        <?php
    }
} else {
    ?>
    <p class="no_info_text">
        Es liegen keine Hinweise vor!
    </p>
<?php } ?>