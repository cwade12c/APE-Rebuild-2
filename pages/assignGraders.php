<?php

$accountID = getCurrentUserID();

$examID = getQueryVar('exam', 0);

$parameters = array('accountID' => $accountID,
                    'examID'    => $examID);

renderPage('pages/assignGraders.twig.html', $parameters);