<?php

/**
 * Create Location class for creating a new location
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateLocation extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('name', 'string');
        parent::registerParameter('seatsReserved', 'integer');
        parent::registerParameter('limitedSeats', 'integer');
        parent::registerParameter('rooms', 'array');

        parent::registerExecution(array($this, 'createLocation'));

        parent::registerValidation('validateLocationName', 'name');
        parent::registerValidation('validateLocationNameDoesNotExist', 'name');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createLocation(string $name, int $seatsReserved, int $limitedSeats,
                                          array $rooms)
    {
        createLocation($name, $seatsReserved, $limitedSeats, $rooms);

        return array(true);
    }
}