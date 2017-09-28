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
 * @param string $name name of location
 *
 * @return bool
 */
function locationNameExists(string $name)
{
    validateLocationName($name);
    return locationNameExistsQuery($name);
}

/**
 * Check if location w/ ID exists
 *
 * @param int $id location ID
 *
 * @return bool
 */
function locationExists(int $id)
{
    validateLocationID($id);
    return locationIDExistsQuery($id);
}

/**
 * Get list of location IDs
 *
 * @return array    list of location IDs
 */
function getLocations()
{
    return getLocationsQuery();
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
function createLocation(string $name, int $seatsReserved, int $limitedSeats,
    array $rooms
) {
    // validate rooms, seats
    validateLocationNameDoesNotExist($name);
    validateLocationRooms($seatsReserved, $limitedSeats, $rooms);

    // TODO: multiple queries, transaction?

    createLocationQuery($name, $seatsReserved, $limitedSeats);
    $id = getLastInsertedID();
    createLocationRoomsQuery($id, $rooms);

    // TODO: validate success?
}

/*
 *
 *
 * TODO: issue with creating initial set of rooms
 * Similar to create/update exam categories
 * Need to validate, then determine
 *  what to add, update, remove
 *  all separate queries
 *  use some helper wrapping functions, multiple queries
 *
 */

/**
 * Update all information about a location
 *
 * @param int    $id                location id
 * @param string $name              new/current name of location
 * @param int    $seatsReserved     number of seats to reserve
 * @param int    $limitedSeats      seats to limit
 * @param array  $rooms             array of rooms to add to location
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
    // validate info
    validateLocationIDExists($id);
    validateLocationRooms($seatsReserved, $limitedSeats, $rooms);

    // TODO: check for conflict with current seating assignments/registration

    // TODO: multiple queries, transaction?
    updateLocationInfoQuery($id, $name, $seatsReserved, $limitedSeats);
    updateLocationRoomsQuery($id, $rooms);

    // TODO: validate success?
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
    // validate info
    validateLocationIDExists($id);
    validateLocationNameDoesNotExist($name);

    $rooms = getLocationRooms($id);
    validateLocationRooms($seatsReserved, $limitedSeats, $rooms);

    updateLocationInfoQuery($id, $name, $seatsReserved, $limitedSeats);
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
    // validate location info
    validateLocationIDExists($id);

    $info = getLocationInformation($id);

    validateLocationRooms(
        $info['seats_reserved'], $info['limited_seats'], $rooms
    );

    updateLocationRoomsQuery($id, $rooms);
}

/**
 * Delete a location and related entries
 * Location must not be used for any exams in a non-archived state
 *
 * @param int $id location id
 */
function deleteLocation(int $id)
{
    validateLocationIDExists($id);

    // TODO: check if location is valid to be deleted
    /// not used in any non-archived exams

    deleteLocationQuery($id);
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
    validateRoomName($name);
    return roomNameExistsQuery($name);
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
    validateRoomID($id);
    return roomIDExistsQuery($id);
}

/**
 * Get list of room IDs
 *
 * @return array
 */
function getRooms()
{
    return getRoomsQuery();
    // TODO: validate something is returned (no false, just empty array)
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
    // TODO: validate room w/ name does not exist?

    createRoomQuery($name, $seats);

    // TODO: validate created?
    // TODO: return room ID?
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

    updateRoomQuery($id, $name, $seats);

    // TODO: validate worked?
}

/**
 * Delete a room
 *
 * @param int $id room ID
 */
function deleteRoom(int $id)
{
    validateRoomIDSafeDelete($id);

    deleteRoomQuery($id);
}

/**
 * Get information about a location
 *
 * @param int $id location ID
 *
 * @return array    associative array w/ information
 *                  'name' => location name
 *                  'reserved_seats' => number of reserved seats
 *                  'limited_seats' => limit of maximum seats
 */
function getLocationInformation(int $id)
{
    // TODO: validating ID may not be necessary
    /// could incorporate into query
    validateLocationIDExists($id);

    return getLocationInformationQuery($id);
}

/**
 * Get list of rooms for a location
 *
 * @param int $id location ID
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
    validateLocationIDExists($id);

    // get rooms list
    $rooms = getLocationRoomsQuery($id);

    // convert keys
    $editedRooms = array();
    foreach ($rooms as $room) {
        $roomEdited = array();
        $roomEdited['id'] = $room['room_id'];
        $roomEdited['seats'] = $room['seats'];

        array_push($editedRooms, $roomEdited);
    }

    return $editedRooms;
}

/**
 * Get information about room
 *
 * @param int $id room ID
 *
 * @return array    associative array w/ information
 *                  'name' => room name
 *                  'seats' => default number of seats in a room
 */
function getRoomInformation(int $id)
{
    validateRoomIDExists($id);

    return getRoomInformationQuery($id);
}