<?php
/**
 * test of exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_testing
 * @subpackage     exams
 */

require_once '../config.php';

// print out list of exams
$exams = getExamsAll();

foreach ($exams as $exam) {
    $categories = getExamCategories($exam);

    //$catStr = implode(',', $categories);
    echo "exam({$exam}): <br>";
    print_r($categories);

    echo "<br><br>";
}

echo "test of exam category changes:<br>";

// determineExamCategoryChanges
// id => points

$currentCategories = array(array('id' => 1, 'points' => 35),
                           array('id' => 2, 'points' => 25),
                           array('id' => 7, 'points' => 30),
                           array('id' => 6, 'points' => 20),
                           array('id' => 4, 'points' => 20),
                           array('id' => 5, 'points' => 30));
$newCategories = array(array('id' => 4, 'points' => 15),
                       array('id' => 2, 'points' => 12),
                       array('id' => 3, 'points' => 4));

echo "<br>current categories-<br>";
print_r($currentCategories);

echo "<br>new categories-<br>";
print_r($newCategories);

echo "<br>differences-<br>";
list(
    $categoriesToRemove, $categoriesToAdd, $categoriesToUpdate
    )
    = determineExamCategoryChanges($currentCategories, $newCategories);

echo "remove categories-<br>";
print_r($categoriesToRemove);

echo "<br>update categories-<br>";
print_r($categoriesToUpdate);

echo "<br>add categories-<br>";
print_r($categoriesToAdd);
echo "<br>";

echo "<br>test build categories to remove where str<br>";
list($str, $params) = buildRemoveExamCategoriesStringParam(
    42, array(4, 5, 6, 7, 2, 4)
);
echo "string: {$str}<br>";
echo "params: <br>";
//var_dump($params);
print_r($params);
echo "<br>";


echo "<br> testing exam category stuff<br>";

$id = 8;

echo "id: {$id}<br>";

$info = getExamInformation($id);
echo "info: <br>";
print_r($info);
echo "<br>";
echo "`start` {$info['start']->format('Y/m/d H:i:s')}<br>";
echo "`cutoff` {$info['cutoff']->format('Y/m/d H:i:s')}<br>";
echo "`length` {$info['length']}<br>";
echo "`passing_grade` {$info['passing_grade']}<br>";
echo "categories:<br>";
$categories = getExamCategories($id);
print_r($categories);
echo "<br>";
echo "<br>";

echo "Updating info<br>";
$newStart = new DateTime('2017-9-21 15:00:00');
$newCutoff = new DateTime('2017-9-21 11:00:00');
$newLength = 150;
$newPassing = 85;
$newCategories = array(array('id' => 1, 'points' => 20),
                       array('id' => 3, 'points' => 40),
                       array('id' => 5, 'points' => 20),
                       array('id' => 6, 'points' => 20));
$newLocationID = 2;

updateExam(
    $id, $newStart, $newCutoff, $newLength, $newPassing, $newLocationID,
    $newCategories
);

echo "Updated<br>";

$info = getExamInformation($id);
echo "new info: <br>";
print_r($info);
echo "<br>";
echo "`start` {$info['start']->format('Y/m/d H:i:s')}<br>";
echo "`cutoff` {$info['cutoff']->format('Y/m/d H:i:s')}<br>";
echo "`length` {$info['length']}<br>";
echo "`passing_grade` {$info['passing_grade']}<br>";
echo "categories:<br>";
$categories = getExamCategories($id);
print_r($categories);
echo "<br>";


echo "<br>attribute details test<br>";


$detailsID = getTableAttributeDetails('accounts', 'id');
$detailsType = getTableAttributeDetails('accounts', 'type');
$detailsNameF = getTableAttributeDetails('accounts', 'f_name');
$detailsNameL = getTableAttributeDetails('accounts', 'l_name');
$detailsEmail = getTableAttributeDetails('accounts', 'email');

$tstr = print_r($detailsID, true);
echo "id: {$tstr}<br>";

$tstr = print_r($detailsType, true);
echo "type: {$tstr}<br>";

$tstr = print_r($detailsNameF, true);
echo "f name: {$tstr}<br>";

$tstr = print_r($detailsNameL, true);
echo "l name: {$tstr}<br>";

$tstr = print_r($detailsEmail, true);
echo "email: {$tstr}<br>";

$detailsDNE = getTableAttributeDetails('accounts', 'invalidAttribute');
$tstr = print_r($detailsDNE, true);
echo "invalid attribute name: {$tstr}<br>";
if (is_null($detailsDNE)) {
    echo "null<br>";
}else{
    echo "nnull<br>";
}
$tt = gettype($detailsDNE);
echo "dne type: {$tt}<br>";


echo "<br><br>EOLFL";