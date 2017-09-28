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
 * @return array    Array of location IDs (ints)
 */
function getLocationsQuery()
{
    // TODO: populate
    return array();
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
    // TODO: populate
    return false;
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
    // TODO: populate
    return false;
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
    // TODO: populate
    // TODO: return new location ID?
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
    // TODO: populate

    // TODO: refer back to createLocationFull() for full implementation
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
    // TODO: populate
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
    // TODO: populate
}

/**
 * Query to delete a location
 *
 * @param int $id Location ID
 */
function deleteLocationQuery(int $id)
{
    // TODO: populate
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
    // TODO: populate
    return false;
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
    // TODO: populate
    /*
     * TODO: experiment with avoiding 'false' query result returns
     * Approach may be odd since queries will return 'false' on issue
     * Maybe can use 'AS' keyword in select query to get definite
     * Test with
     */
    return false;
}

/**
 * Get list of Room IDs that exist
 *
 * @return array    Array of Room IDs
 */
function getRoomsQuery()
{
    // TODO: populate
}

/**
 * Query to create a room
 *
 * @param string $name  Room name
 * @param int    $seats Room seat count
 */
function createRoomQuery(string $name, int $seats)
{
    // TODO: populate
    // TODO: return room ID?
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
    // TODO: populate
}

/**
 * Delete a room
 *
 * @param int $id Room ID
 */
function deleteRoomQuery(int $id)
{
    // TODO: populate
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
    // TODO: populate
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
    // TODO: populate
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
    // TODO: populate
}