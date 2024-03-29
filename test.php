<?php
/*
  Author: Carlos Servín Romero
  email: carlos@servin.mx, cservinmx@gmail.com
  creado: 21/08/2019
  update: 21/08/2019

*/

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require('configdb.php');

$user="pramirez@hotmail.com";
//$query = "select * from usuario WHERE USU_A_PATERNO='Gonzalez' ";
//$query = "select * from usuario";
$query ="SELECT USUA_CORREO, usua_usuario, usua_passemail FROM ADSI_CAT_USUA cat1
         INNER JOIN RISC_REL_USUA cat2 ON cat1.USUA_IDUSUARIO=cat2.USUA_IDUSUARIO
         WHERE usua_correo='".$user."'";

$c = oci_connect($username, $password, $database);
if (!$c) {
    $m = oci_error();
    trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
}

$s = oci_parse($c, $query);
if (!$s) {
    $m = oci_error($c);
    trigger_error('Could not parse statement: '. $m['message'], E_USER_ERROR);
}
$r = oci_execute($s);
if (!$r) {
    $m = oci_error($s);
    trigger_error('Could not execute statement: '. $m['message'], E_USER_ERROR);
}

echo "<table border='1'>\n";
$ncols = oci_num_fields($s);
echo "<tr>\n";
for ($i = 1; $i <= $ncols; ++$i) {
    $colname = oci_field_name($s, $i);
    echo "<th><b>".htmlspecialchars($colname,ENT_QUOTES|ENT_SUBSTITUTE)."</b></th>\n";
}
echo "</tr>\n";

while (($row = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "<td>";
        echo $item!==null?htmlspecialchars($item, ENT_QUOTES|ENT_SUBSTITUTE):"&nbsp;";
        echo "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

?>
