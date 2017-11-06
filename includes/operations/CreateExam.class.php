<?php

/**
 * Operation to create a regular exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'createExam'));

        parent::registerParameter('start', 'string');
        parent::registerParameter('cutoff', 'string');
        parent::registerParameter('locationID', 'integer');
        parent::registerParameter('categories', 'array');
        parent::registerParameter('passingGrade', 'integer');
        parent::registerParameter('length', 'integer');

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

    public static function createExam(string $start, string $cutoff,
        int $locationID, array $categories, int $passingGrade, int $length
    ) {
        $start = DateTime::createFromFormat(DATETIME_FORMAT, $start);
        $cutoff = DateTime::createFromFormat(DATETIME_FORMAT, $cutoff);

        createExam(
            $start, $cutoff, $length, $passingGrade, $categories, $locationID
        );

        $id = getLastInsertedID();
        return array('examID' => $id);
    }
}