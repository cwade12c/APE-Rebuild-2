<?php

/**
 * To search up exams, only returns IDs
 * More to be used by other operations
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamSearch extends Operation
{
    function __construct()
    {
        // only public/safe information returned
        parent::requireLogin(false);

        parent::registerExecution(array($this, 'search'));

        parent::registerParameter('states', 'integer');
        parent::registerParameter('types', 'integer');
        parent::registerOptionalParameter('teacherID', 'string', null);

        parent::registerValidation('validateSearchStates', 'states');
        parent::registerValidation('validateSearchType', 'types');
        parent::registerValidation(array($this, 'validateTeacherID'), 'teacherID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    /**
     * Search for exams
     *
     * @param int    $states    define set of states, check constants for
     *                          allows to search for
     *                          - all
     *                          - non-archived
     *                          - archived
     *                          - grading
     *                          - upcoming
     *                          - open
     * @param int    $types     defined search types, check constants for
     *                          allows to search for-
     *                          - regular exams
     *                          - in-class exams
     *                          - both
     * @param string $teacherID optional, can be null
     *                          if the type includes in-class exams,
     *                          search by teacher ID
     *
     * @return array            'exams' => resulting exams, details
     *                          check 'ExamDetails' for keys
     *
     *
     */
    public static function search(int $states, int $types, string $teacherID = null)
    {
        $ids = getExamsExtended($states, $types, $teacherID);
        $exams = array_map(array(new ExamDetails(), 'getExamInformation'), $ids);
        return array('exams' => $exams);
    }

    public static function validateTeacherID(string $teacherID = null) {
        if ($teacherID != null) {
            validateTeacherID($teacherID);
        }
        return true;
    }
}