<?php

/**
 * Update Default Categories class to update the set of Default Categories
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateDefaultCategories extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('idList', 'array');

        parent::registerExecution(array($this, "updateDefaultCategories"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateDefaultCategories(array $idList)
    {
        setDefaultCategories($idList);
        return array(true);
    }
}