<?php

$graderID = getCurrentUserID();

$examID = getQueryVar('exam', 0);
$categoryID = getQueryVar('category', 0);

$parameters = array('graderID'   => $graderID,
                    'examID'     => $examID,
                    'categoryID' => $categoryID);

renderPage('pages/examCategoryGrading.twig.html', $parameters);