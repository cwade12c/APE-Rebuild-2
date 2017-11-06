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
    validateLocationNameDoesNotExist($name);
    validateLocationRooms($seatsReserved, $limitedSeats, $rooms);

    // TODO: multiple queries, transaction?

    createLocationQuery($name, $seatsReserved, $limitedSeats);
    $id = getLastInsertedID();
    createLocationRoomsQuery($id, $rooms);

    // TODO: validate success?
}

/**
 * Update the name of a location
 *
 * @param int    $id                location id
 * @param string $name              new name of location
 */
function updateLocationName(int $id, string $name) {
    validateLocationIDExists($id);
    updateLocationNameQuery($id, $name);
}

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
    validateLocationIDExists($id);
    validateLocationRooms($seatsReserved, $limitedSeats, $rooms);

    // TODO: check for conflict with current seating assignments/registration

    // TODO: multiple queries, transaction?
    updateLocationInfoQuery($id, $name, $seatsReserved, $limitedSeats);
    updateLocationRoomsExt($id, $rooms);

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

    updateLocationRoomsExt($id, $rooms);
}

/**
 * Internal function used to perform action of updating location rooms
 * Not intended for outside use
 *
 * @param int   $id       Location ID
 * @param array $newRooms Array of rooms to add/update (removes others)
 *                        Element format
 *                        'id' => room ID
 *                        'seats' => room seats
 */
function updateLocationRoomsExt(int $id, array $newRooms)
{
    // TODO: validate any conflicts w/ current seating ?

    $currentRooms = getLocationRooms($id);

    // determine changes necessary
    list(
        $roomsToRemove, $roomsToAdd, $roomsToUpdate
        )
        = determineLocationRoomChanges($currentRooms, $newRooms);

    // remove rooms
    removeLocationRoomsQuery($id, $roomsToRemove);

    // update rooms
    updateLocationRoomsQuery($id, $roomsToUpdate);

    // add room
    createLocationRoomsQuery($id, $roomsToAdd);

    // TODO: check for success?
}

/**
 * Determine changes necessary to update the rooms for a location
 *
 * @param array $currentRooms       Array of current location rooms, element format
 *                                  'id' => room ID
 *                                  'seats' => room seats
 * @param array $newRooms           Array
 *
 * @return array
 */
function determineLocationRoomChanges(array $currentRooms, array $newRooms)
{
    // build list of current room IDs
    $currentRoomIDs = array_map(
        'mapLocationRoomIDsOut', $currentRooms
    );

    // build list of new room IDs
    $newRoomIDs = array_map(
        'mapLocationRoomIDsOut', $newRooms
    );

    // determine changes necessary
    $roomsToRemove = array();
    $roomsToAdd = array();
    $roomsToUpdate = array();

    // check new room IDs
    foreach ($newRoomIDs as $roomID) {
        // check for update
        if (in_array($roomID, $currentRoomIDs)) {
            array_push($roomsToUpdate, $roomID);
        } else {
            array_push($roomsToAdd, $roomID);
        }
    }

    // check old room IDs for removals
    foreach ($currentRoomIDs as $roomID) {
        if (!in_array($roomID, $newRoomIDs)) {
            array_push($roomsToRemove, $roomID);
        }
    }

    // build return arrays
    $roomsToAdd = mapLocationRoomsBack($roomsToAdd, $newRooms);
    $roomsToUpdate = mapLocationRoomsBack($roomsToUpdate, $newRooms);

    return array($roomsToRemove, $roomsToAdd, $roomsToUpdate);
}

/**
 * Helper function for determineLocationRoomChanges();
 * Used by array_map() to pull all room IDs
 *
 * @param array $room       single room in format
 *                          array(
 *                          'id' => room id
 *                          'seats' => room seats
 *                          )
 *
 * @return int              room id
 */
function mapLocationRoomIDsOut(array $room)
{
    return $room['id'];
}

/**
 * Helper function for determineLocationRoomChanges()
 * Used to map an array of room ID back to an array of format
 * array(ID, seats)
 *
 * @param array $roomIDs        array of location room IDs
 * @param array $rooms          array to grab seats from, with format of
 *                              'id' => room ID
 *                              'seats' => room seats
 *
 * @return array        array with elements in format of
 *                          'id' => room ID
 *                          'seats' => room seats
 */
function mapLocationRoomsBack(array $roomIDs, array $rooms)
{
    // cannot use array_map, due to issues with an array as an argument
    // will pass each element of that array along, not whole array
    $normalizedRooms = array();
    foreach ($roomIDs as $id) {
        // find category information
        foreach ($rooms as $room) {
            if ($room['id'] == $id) {
                array_push($normalizedRooms, $room);
                break;
            }
        }
        // safe to assume all room IDs will map back correctly
        // TODO: check for anyway?
    }

    return $normalizedRooms;
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

    // if relations set correctly
    // don't have to worry about affecting other tables
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
    $roomIDs = getRoomsQuery();
    $ids = array();
    foreach ($roomIDs as $roomIDArr) {
        array_push($ids, $roomIDArr['id']);
    }
    return $ids;

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

    // TODO: check if room deletion affects locations/exams

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
 * Get the max number of seats from all location rooms
 * Does take into account the limited seats max
 *
 * @param int $id Location ID
 *
 * @return int    Max number of seats
 */
function getLocationRoomsMaxSeats(int $id)
{
    // count max for rooms
    $rooms = getLocationRooms($id);
    $roomsMax = 0;
    foreach ($rooms as $room) {
        $roomsMax += $room['seats'];
    }

    // check for limited seating
    $info = getLocationInformation($id);
    if ($info['limited_seats'] > 0) {
        return min($roomsMax, $info['limited_seats']);
    }

    return $roomsMax;
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
    return getRoomInformationQuery($id);
}

/**
 * Check if location has the given room
 *
 * @param int $locationID Location ID
 * @param int $roomID     Room ID
 *
 * @return bool             If location has room
 */
function locationHasRoom(int $locationID, int $roomID)
{
    $rooms = getLocationRooms($locationID);
    foreach ($rooms as $room) {
        if ($room['id'] == $roomID) {
            return true;
        }
    }
    return false;
    // TODO: can make query just for this for efficiency
}

/**
 * Get the number of seats for a location/room
 *
 * @param int $locationID Location ID
 * @param int $roomID     Room ID
 *
 * @return int            Seat count
 *
 * @throws InvalidArgumentException If location does not contain the room
 */
function getLocationRoomSeats(int $locationID, int $roomID)
{
    $rooms = getLocationRooms($locationID);
    foreach ($rooms as $room) {
        if ($room['id'] == $roomID) {
            return $room['seats'];
        }
    }
    throw new InvalidArgumentException(
        sprintf("Location(%d) does not contain room(%d)", $locationID, $roomID)
    );
    // TODO: can make query just for this for efficiency
}
