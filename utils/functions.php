<?php
function getData($con, $sql, $column_name) {
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    return $row[$column_name];
}


function getItemsNav($items_nav, $page) {
    $items_nav[$page] = 'active';
    return $items_nav;
}
?>