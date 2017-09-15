<?php
/**
 * Functions for locations/rooms in database
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Check if location w/ name exists
 *
 * @param string $name  name of location
 *
 * @return bool
 */
function locationNameExists(string $name)
{
    // validate location name
    // TODO: populate
    return false;
}

/**
 * Check if location w/ ID exists
 *
 * @param int $id   location ID
 *
 * @return bool
 */
function locationExists(int $id)
{
    // validate id
    // TODO: populate
    return true;
}

/**
 * Get list of location IDs
 *
 * @return array    list of location IDs
 */
function getLocations()
{
    // TODO: populate
    return array();
}

/**
 * Create location w/ given name
 *
 * @param string $name name of location
 */
function createLocation(string $name)
{
    validateLocationName($name);
}

/**
 * Creates a location w/ rooms and seat limitations set
 *
 * @param string $name              name of location
 * @param int    $seatsReserved     number of seats to reserve
 * @param int    $limitedSeats      limit the total number of seats to this
 * @param array  $rooms             array of rooms to add to location
 *                                  all rooms must exist before hand
 *                                  element format of
 *                                  array(
 *                                  'id' => room id,
 *                                  'seats' => overridden number of room seats
 *                                  )
 */
function createLocationFull(string $name, int $seatsReserved, int $limitedSeats,
    array $rooms
) {
    // validate rooms, seats

    // create location
    // add/create rooms

    // TODO: populate
}

/**
 * Update all information about a location
 *
 * @param int    $id            location id
 * @param string $name          new/current name of location
 * @param int    $seatsReserved number of seats to reserve
 * @param int    $limitedSeats  seats to limit
 * @param array  $rooms         array of rooms to add to location
 *                                  all rooms must exist before hand
 *                                  element format of
 *                                  array(
 *                                  'id' => room id,
 *                                  'seats' => overridden number of room seats
 *                                  )
 */
function updateLocationFull(int $id, string $name, int $seatsReserved,
    int $limitedSeats, array $rooms
) {
    // validate information
    // update location information
    // update location rooms
    // TODO: populate
}

/**
 * Helper function for updateLocationFull()
 * Not intended to outside use
 * Updates base information about a location
 *
 * @param int    $id
 * @param string $name
 * @param int    $seatsReserved
 * @param int    $limitedSeats
 */
function updateLocation(int $id, string $name, int $seatsReserved,
    int $limitedSeats
) {
    // TODO: populate
}

/**
 * Helper function for updateLocationFull()
 * Not intended to outside use
 * Updates rooms for a location
 *
 * @param int   $id     location id
 * @param array $rooms  array of rooms to update/add
 *                      all must exist
 *                      element format:
 *                      array(
 *                      'id' => room id,
 *                      'seats' => overridden number of seats
 *                      )
 */
function updateLocationRooms(int $id, array $rooms)
{
    // TODO: populate
}

/**
 * Delete a location and related entries
 * Location must not be used for any exams in a non-archived state
 *
 * @param int $id location id
 */
function deleteLocation(int $id)
{
    validateLocationID($id);

    // TODO: check if location is valid to be deleted
    /// not used in any non-archived exams

    // TODO: delete location
}

/**
 * Check if room w/ name exists
 *
 * @param string $name
 *
 * @return bool
 */
function roomNameExists(string $name)
{
    // validate name
    // TODO: populate
    return false;
}

/**
 * Check if room w/ ID exists
 *
 * @param int $id
 *
 * @return bool
 */
function roomExists(int $id)
{
    // validate id
    // TODO: populate
    return false;
}

/**
 * Get list of room IDs
 *
 * @return array
 */
function getRooms()
{
    // TODO: populate
    return array();
}

/**
 * Create a room
 *
 * @param string $name  name of room
 * @param int    $seats number of seats available in room
 */
function createRoom(string $name, int $seats)
{
    validateRoomName($name);

    // TODO: create room
}

/**
 * Update room information
 *
 * @param int    $id    room id to update
 * @param string $name  new/current name
 * @param int    $seats new/current seat count
 */
function updateRoom(int $id, string $name, int $seats)
{
    validateRoomID($id);
    validateRoomName($name);

    // check if seat numbers change
    /// check if changing seat numbers will cause issues

    // TODO: update room
}

/**
 * Delete a room
 *
 * @param int $id   room ID
 */
function deleteRoom(int $id)
{
    validateRoomID($id);
    // validate room not used in any locations
    // TODO: delete room
}

/**
 * Get information about a location
 *
 * @param int $id   location ID
 *
 * @return array    associative array w/ information
 *                  'name' => location name
 *                  'reserved_seats' => number of reserved seats
 *                  'limited_seats' => limit of maximum seats
 */
function getLocationInformation(int $id)
{
    // TODO: populate
}

/**
 * Get list of rooms for a location
 *
 * @param int $id   location ID
 *
 * @return array array of rooms
 *               element format:
 *               array(
 *               'id' => room id,
 *               'seats' => set number of seats for room at location
 *               )
 */
function getLocationRooms(int $id)
{
    // TODO: populate
}

/**
 * Get information about room
 *
 * @param int $id   room ID
 *
 * @return array    associative array w/ information
 *                  'name' => room name
 *                  'seats' => default number of seats in a room
 */
function getRoomInformation(int $id)
{
    // TODO: populate
}