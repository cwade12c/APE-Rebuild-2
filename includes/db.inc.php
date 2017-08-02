<?php

function executeQuery($query) {
    global $db;

    $sql = $db->prepare($query);
    $sql->execute();

    return $sql;
}

function getQueryResult($sql) {
    $result = $sql->fetchColumn();
    return $result;
}

function getQueryResults($sql) {
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

?>