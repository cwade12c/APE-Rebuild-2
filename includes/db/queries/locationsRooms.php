<?php
/**
 * Query functions for locations and rooms
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get list of location IDs
 *
 * @return array    Array of location IDs (int)
 */
function getLocationsQuery()
{
    $query = "SELECT `id` FROM `locations`";
    $sql = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Query to check if location name exists
 *
 * @param string $name Location name
 *
 * @return bool
 */
function locationNameExistsQuery(string $name)
{
    $query = "SELECT (:name IN (SELECT `name` FROM `locations`))";

    $sql = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR)
        )
    );

    return getQueryResult($sql);
}

/**
 * Query to check if location ID exists
 *
 * @param int $id Location ID
 *
 * @return bool
 */
function locationIDExistsQuery(int $id)
{
    $query = "SELECT (:id IN (SELECT `id` FROM `locations`))";

    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

/**
 * Query to create a location w/ given information
 *
 * @param string $name          Location name
 * @param int    $seatsReserved Number of reserved seats
 * @param int    $limitedSeats  Limited seats number
 */
function createLocationQuery(string $name, int $seatsReserved, int $limitedSeats
) {
    $query = "INSERT INTO `locations` "
        . " (`name`, `reserved_seats`, `limited_seats`) "
        . " VALUES (:name, :reservedSeats, :limitedSeats);";
    $sql = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR),
            array(':reservedSeats', $seatsReserved, PDO::PARAM_INT),
            array(':limitedSeats', $limitedSeats, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to create rooms for a location
 *
 * @param int   $id         Location ID
 * @param array $rooms      Array of rooms to create, element format
 *                          'id' => room id
 *                          'seats' => room seats
 */
function createLocationRoomsQuery(int $id, array $rooms)
{
    if (count($rooms) <= 0) {
        return;
    }

    // build values string and params
    list($setStr, $params) = buildLocationRoomsValuesString($id, $rooms);

    // build, execute query string
    $query = sprintf(
        "INSERT INTO `locations_rooms` "
        . " (`location_id`, `room_id`, `seats`) "
        . " VALUES %s", $setStr
    );
    $sql = executeQuery($query, $params);
}

/**
 * Helper function for createLocationRoomsQuery()
 * Not intended for outside use
 * Query to create location rooms
 *
 * @param int   $id     Location ID
 * @param array $rooms  Array of rooms, element format
 *                      'id' => room id
 *                      'seats' => room seats set
 *
 * @return array        2 elements returned
 *                      values string, used for insert statement
 *                      parameters, for prepared query
 */
function buildLocationRoomsValuesString(int $id, array $rooms)
{
    $params = array();
    $values = array();

    array_push($params, array(':id', $id, PDO::PARAM_INT));

    foreach ($rooms as $i => $room) {
        // determine param names
        $roomIDKey = sprintf(':room%d', $i);
        $roomSeatsKey = sprintf(':seats%d', $i);
        array_push(
            $values, sprintf('(:id, %s, $s)', $roomIDKey, $roomSeatsKey)
        );
        // add parameters
        array_push($params, array($roomIDKey, $room['id'], PDO::PARAM_INT));
        array_push(
            $params, array($roomSeatsKey, $room['seats'], PDO::PARAM_INT)
        );
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Query to update location information (row entry)
 *
 * @param int    $id            Location ID
 * @param string $name          Location name
 * @param int    $seatsReserved Number of reserved seats
 * @param int    $limitedSeats  Limited seats number
 */
function updateLocationInfoQuery(int $id, string $name, int $seatsReserved,
    int $limitedSeats
) {
    $query = "UPDATE `locations` "
        . " SET `name` = :name, `reserved_seats` = :reservedSeats, "
        . " `limited_seats`=:limitedSeats "
        . " WHERE `id` = :id";

    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':name', $name, PDO::PARAM_STR),
            array(':reservedSeats', $seatsReserved, PDO::PARAM_INT),
            array(':limitedSeats', $limitedSeats, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to update rooms for a location
 *
 * @param int   $id     Location ID
 * @param array $rooms  Array of rooms, element format
 *                      'id' => room id,
 *                      'seats' => overridden number of seats
 */
function updateLocationRoomsQuery(int $id, array $rooms)
{
    if (count($rooms) <= 0) {
        return;
    }

    list($setStr, $params) = buildUpdateLocationRoomsStringParams(
        $id, $rooms
    );

    $query = sprintf(
        "UPDATE `location_rooms` "
        . " SET `seats` = CASE %s ELSE `seats` END "
        . " WHERE `location_id` = :id", $setStr
    );

    $sql = executeQuery($query, $params);
}

/**
 * Helper function for updateLocationRoomsQuery()
 * Not intended for outside use
 * Builds string for update query and parameters
 *
 * @param int   $id
 * @param array $rooms
 */
function buildUpdateLocationRoomsStringParams(int $id, array $rooms)
{
    $params = array();
    array_push($params, array(':id', $id, PDO::PARAM_INT));

    // go through rooms, build params
    // and 'when then' set string
    $whenThenStrings = array();
    foreach ($rooms as $i => $room) {
        // build key names
        $keyNameID = sprintf(':id%d', $i);
        $keyNameSeats = sprintf(':seats%d', $i);
        // build params
        array_push($params, array($keyNameID, $room['id'], PDO::PARAM_INT));
        array_push(
            $params, array($keyNameSeats, $room['seats'], PDO::PARAM_INT)
        );
        // build case string
        array_push(
            $whenThenStrings, sprintf(
                'WHEN `room_id` = %s THEN %s',
                $keyNameID, $keyNameSeats
            )
        );
    }

    // build set when cases string
    $setStr = implode(' ', $whenThenStrings);

    return array($setStr, $params);
}

/**
 * Query to delete rooms from a location
 *
 * @param int   $id    Location ID
 * @param array $rooms Array of room IDs
 */
function removeLocationRoomsQuery(int $id, array $rooms)
{
    // check that rooms available
    if (count($rooms) <= 0) {
        return;
    }

    list($whereStr, $params) = buildRemoveLocationRoomsStringParam(
        $id, $rooms
    );

    $query = sprintf(
        "DELETE FROM `location_rooms` "
        . " WHERE %s", $whereStr
    );

    $sql = executeQuery($query, $params);
}

/**
 * Helper function for removeLocationRoomsQuery()
 * Not intended for outside use
 * Builds removal string and params for query
 *
 * @param int   $id    Location ID
 * @param array $rooms Array of room IDs
 */
function buildRemoveLocationRoomsStringParam(int $id, array $rooms)
{
    $params = array();
    array_push($params, array(':id', $id, PDO::PARAM_INT));

    $roomIDArr = array();

    // build params from room IDs
    foreach ($rooms as $i => $roomID) {
        $roomIDKey = sprintf(':roomID%d', $i);
        array_push($params, array($roomIDKey, $roomID, PDO::PARAM_INT));
        array_push($roomIDArr, $roomIDKey);
    }

    // build string
    $roomArrStr = implode(',', $roomIDKey);
    $whereStr = sprintf(
        '(`id` = :id) && (`room_id` in (%s))', $roomArrStr
    );

    return array($whereStr, $params);
}

/**
 * Query to delete a location
 *
 * @param int $id Location ID
 */
function deleteLocationQuery(int $id)
{
    $query = "DELETE FROM `locations` WHERE `id` = :id";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));
}

/**
 * Check if room w/ name exists
 *
 * @param string $name Room name
 *
 * @return bool
 */
function roomNameExistsQuery(string $name)
{
    $query = "SELECT (:name IN (SELECT `name` FROM `rooms`))";

    $sql = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR)
        )
    );

    return getQueryResult($sql);
}

/**
 * Check if room w/ ID exists
 *
 * @param int $id Room ID
 *
 * @return bool
 */
function roomIDExistsQuery(int $id)
{
    $query = "SELECT (:id IN (SELECT `id` FROM `rooms`))";

    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

/**
 * Get list of Room IDs that exist
 *
 * @return array    Array of Room IDs
 */
function getRoomsQuery()
{
    $query = "SELECT `id` FROM `rooms`";
    $sql = executeQuery($query);

    return getQueryResults($sql);
}

/**
 * Query to create a room
 *
 * @param string $name  Room name
 * @param int    $seats Room seat count
 */
function createRoomQuery(string $name, int $seats)
{
    $query = "INSERT INTO `rooms`(`name`, `seats`) "
        . " VALUES (:name, :seats);";
    $sql = executeQuery(
        $query, array(
            array(':name', $name, PDO::PARAM_STR),
            array(':seats', $seats, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to update room information
 *
 * @param int    $id    Room ID
 * @param string $name  Room name
 * @param int    $seats Room seat count
 */
function updateRoomQuery(int $id, string $name, int $seats)
{
    $query = "UPDATE `rooms` "
        . " SET `name` = :name, `seats` = :seats "
        . " WHERE `id` = :id";

    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':name', $name, PDO::PARAM_STR),
            array(':seats', $seats, PDO::PARAM_INT)
        )
    );
}

/**
 * Delete a room
 *
 * @param int $id Room ID
 */
function deleteRoomQuery(int $id)
{
    $query = "DELETE FROM `rooms` WHERE `id` = :id";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));
}

/**
 * Get information on a location ID
 *
 * @param int $id Location ID
 *
 * @return array    associate array of location information in format:
 *                      'name' => location name
 *                      'reserved_seats' => reserved seat count
 *                      'limited_seats' => limited seat count
 */
function getLocationInformationQuery(int $id)
{
    $query = "SELECT `name`, `reserved_seats`, `limited_seats` "
        . " FROM `locations` WHERE `id` = :id";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));
    return getQueryResultRow($sql);
}

/**
 * Used to get the list of rooms for a location
 *
 * @param int $id Location ID
 *
 * @return array    associative array of rooms in format
 *                      'room_id' => room id
 *                      'seats' => room seats set
 */
function getLocationRoomsQuery(int $id)
{
    $query = "SELECT `room_id`, `seats` "
        . " FROM `location_rooms` WHERE `location_id` = :id";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));
    return getQueryResults($sql);
}

/**
 * Used to get information on a room
 *
 * @param int $id Room ID
 *
 * @return array    Associative array of room information in format
 *                      "name" => room name
 *                      "seats" => room seats
 */
function getRoomInformationQuery(int $id)
{
    $query = "SELECT `name`, `seats` "
        . " FROM `rooms` WHERE `id` = :id";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));
    return getQueryResultRow($sql);
}