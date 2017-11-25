<?php

$accountID = getCurrentUserID();

$examID = getQueryVar('exam', 0);

$parameters = array('accountID' => $accountID,
                    'examID'    => $examID);

renderPage('pages/registerStudents.twig.html', $parameters);