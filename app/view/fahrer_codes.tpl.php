<?php
$fahrer_codes = $fahrerCodesVerwaltung->findeAlle();
?>

<h5>Fahrer Codes zuweisen:</h5>
<form method="post" action="index.php?action=speichere_fahrer">

    <input type="submit" value="speichern" />
    <table>
        <tr>
            <th>Code</th>
            <th>Fahrer Name</th>
        </tr>
        <?php foreach ($fahrer_codes as $fahrer) { ?>
            <tr>
                <td><?php echo $fahrer->getCode() ?></td>
                <td>
                    <input type="text" name="fahrer_codes[<?php echo $fahrer->getCode() ?>]" value="<?php echo $fahrer->getName() ?>" />
                </td>
            </tr>
        <?php }
        ?>
    </table>
    <input type="submit" value="speichern" />
</form>