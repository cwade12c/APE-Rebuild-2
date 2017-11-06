<?php

/**
 * Operation for a Student to register their account for an Exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateInClassExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(array($this, 'createExam'));

        parent::registerParameter('teacherID', 'string');
        parent::registerParameter('start', 'string');
        parent::registerParameter('cutoff', 'string');
        parent::registerParameter('locationID', 'integer');
        parent::registerParameter('categories', 'array');
        parent::registerParameter('passingGrade', 'integer');
        parent::registerParameter('length', 'integer');

        parent::registerValidation('validateTeacherID', 'teacherID');
        parent::registerValidation('validateStringIsDateTime', 'start');
        parent::registerValidation('validateStringIsDateTime', 'cutoff');
        parent::registerValidation('validateDates', array('start', 'cutoff'));
        parent::registerValidation('validateExamLength', 'length');
        parent::registerValidation(
            'validateExamCategories', array('passingGrade', 'categories')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createExam(string $teacherID, DateTime $start,
        DateTime $cutoff, int $locationID, array $categories, int $passingGrade,
        int $length
    ) {
        createInClassExam(
            $start, $cutoff, $length, $passingGrade, $categories, $locationID,
            $teacherID
        );

        $id = getLastInsertedID();
        return array('examID' => $id);
    }
}