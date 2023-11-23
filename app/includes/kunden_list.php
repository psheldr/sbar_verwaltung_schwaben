<?php
require_once __DIR__ . '/../Dotenv.php';

$return_arr = array();
$search_for_arr = explode(' ', $_REQUEST['term']);
if (isset($_REQUEST['term'])) {
    try {
        $conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM kunde WHERE ";
        $c = 1;
        foreach ($search_for_arr as $search_term) {
            $term = "'%" . utf8_decode(trim($search_term)) . "%'";
            if ($c > 1) {
                $sql .= " AND ";
            }
            $sql .= "(name LIKE " . $term.' OR kundennummer LIKE '.$term.')';
            $c++;
        }
        // $sql .= ' OR kundennummer LIKE :term OR plz LIKE :term OR ort LIKE :term ORDER BY name ASC';
        $sql .= ' AND einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 ORDER BY aktiv DESC, name ASC';
        $stmt = $conn->prepare($sql);
        //$stmt->execute(array('term' => '%' . utf8_decode(trim($_REQUEST['term'])) . '%'));
        $stmt->execute();
        $alle_ergebnisse = array();
        $return_arr_inaktiv = array();
        $return_arr_aktiv = array();
        while ($row = $stmt->fetch()) {
            $label_style = 'font-weight: bold;';
            $para = '&kids=' . $row['id'];
            $goto = 'cockpit';
            $add_label = '';

            if (isset($_REQUEST['goto'])) {
                $goto = $_REQUEST['goto'];
            }
if ($row['kundennummer']) {
                    $add_label = ' ['.$row['kundennummer'].']';
}
                $alle_ergebnisse[] = $row['id'];
            if ($row['aktiv'] == 0) {
                $add_label = ' (inaktiv)';
                $label_style = 'color: red;';
                $return_arr_inaktiv[] = array(
                    "label" => '<span style="' . $label_style . '">' . utf8_encode($row['name']) . /* ' [' . $row['id'] . ']' . */ $add_label . '</span>',
                    "url" => 'index.php?action=' . $goto . $para
                );
            } else {
                $return_arr_aktiv[] = array(
                    "label" => '<span style="' . $label_style . '">' . utf8_encode($row['name']) . /* ' [' . $row['id'] . ']' . */ $add_label . '</span>',
                    "url" => 'index.php?action=' . $goto . $para
                );
            }

         /*   $return_arr[] = array(
                "label" => '<span style="' . $label_style . '">' . utf8_encode($row['name']) . $add_label . '</span>',
                "url" => 'index.php?action=' . $goto . $para
            );*/
        }




        $alle_ergebnisse_string = implode('-', $alle_ergebnisse);
       if (count($return_arr_aktiv)+count($return_arr_inaktiv) > 1) {
            $return_arr[] = array(
                "label" => '<span style="display:block;width: 100%;font-size: 15px;color:green;font-weight:bold;">' . utf8_encode('Alle Ergebnisse aufrufen') . '</span>',
                "url" => 'index.php?action=' . $goto . '&kids=' . $alle_ergebnisse_string
            );
        }
       // $return_arr = $return_arr_aktiv;
        $return_arr = array_merge($return_arr,$return_arr_aktiv);
        $return_arr = array_merge($return_arr,$return_arr_inaktiv);

    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }


    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
}
?>
