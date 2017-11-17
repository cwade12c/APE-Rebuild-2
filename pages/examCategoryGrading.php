<?php

$id = getCurrentUserID();

$query = getQuery();
function getIfExists($query, $key) {
    if (isset($query[$key])) {
        return $query[$key];
    }else{
        return null;
    }
}

$exam = getIfExists($query, 'exam');
$category = getIfExists($query, 'category');

$parameters = array(
    'id' => $id,
    'exam' => $exam,
    'category' => $category,
    'isGrader' => accountIsGrader($id)
);

// feed parameters into

// id
// id valid (grader, something)

// queries
// exam given
// category given

// exam, category valid
// exam in grading state

renderPage("pages/examCategoryGrading.twig.html", $parameters);