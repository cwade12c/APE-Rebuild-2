<?php

require_once('../../config.php');
enforceAuthentication();
global $params;

if(isset($_GET['examId'])) {
    $examId = $_GET['examId'];
    $assignedSeats = getAssignedSeats($examId);
    foreach($assignedSeats as &$value) {
        if($value['room_id'] != NULL) {
            $roomInformation = getRoomInformation($value['room_id']);
            $value['room_name'] = $roomInformation['name'];
        }
        else {
            $value['room_name'] = 'Unassigned';
        }
    }
    $registeredCount = getRegisteredCount($examId);
    $state = getExamState($examId);
    $examInformation = getExamInformation($examId);
    $locationInformation = getLocationInformation($examInformation['location_id']);

    $parameters = array(
        'assignedSeats' => $assignedSeats,
        'registeredCount' => $registeredCount,
        'examInformation' => $examInformation,
        'locationInformation' => $locationInformation
    );

    renderPage("modals/exam-details.twig.html", $parameters);
}