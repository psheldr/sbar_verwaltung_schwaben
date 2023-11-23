<?php

require_once 'Dotenv.php';

    $return_arr = array();
if (isset($_REQUEST['term'])) {

    try {
        $conn = new PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'].";", $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare('SELECT * FROM traeger
		WHERE bezeichnung LIKE :term OR asp LIKE :term OR email LIKE :term OR email2 LIKE :term OR email3 LIKE :term OR email4 LIKE :term OR email5 LIKE :term OR matchcode LIKE :term OR referenz LIKE :term ORDER BY bezeichnung ASC');
        $stmt->execute(array('term' => '%' . utf8_decode($_REQUEST['term']) . '%'));

            $para = '';
            $goto = 'traeger';

            if (isset($_REQUEST['goto'])) {
                $goto = $_REQUEST['goto'];
                //$para = '&cid=' . $row['caterer_id'];
            }

            if (isset($_REQUEST['uid']) && isset($_REQUEST['pid'])) {
                $para = '&uid=' . $_REQUEST['uid'].'&pid=' . $_REQUEST['pid'].'#amt_sel';
            }

        while ($row = $stmt->fetch()) {
            $return_arr[] = array(
                "label" => utf8_encode($row['bezeichnung']. ' <span class="size-12">(id: ' .$row['id'].')</span>'),
                "url" => 'index.php?action='.$goto.'&amtid='.$row['id'].$para
            );
        }
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }


    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
}
?>
