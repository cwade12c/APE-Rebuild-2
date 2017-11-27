<?php

/**
 * Gets basic exam details, just information on an exam entry
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamDetails extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER)
        );

        parent::registerExecution(array($this, 'getExamInformation'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamInformation(int $examID)
    {
        $info = getExamInformation($examID);

        list(
            $isRegular, $teacherID
            )
            = ExamDetailsFull::getExamInClassInformation($info);

        return array(
            'examID'       => $examID,
            'start'        => $info['start'],
            'cutoff'       => $info['cutoff'],
            'locationID'   => $info['locationID'],
            'length'       => $info['length'],
            'passingGrade' => $info['passingGrade'],
            'state'        => $info['state'],
            'isRegular'    => $isRegular,
            'teacherID'    => $teacherID
        );
    }
}