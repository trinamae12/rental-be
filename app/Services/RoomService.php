<?php
namespace App\Services;

use App\Models\Room;

class RoomService 
{
    public function fetchAllRooms() {
        $rooms = Room::all();

        return[
            'success' => true,
            'rooms' => $rooms
        ];
    }

    /**
     * Create new room
     * 
     * @param array $room Room data including room_name, room_description, room_month_price
     * @return Room The newly created room
     */

    public function createRoom(array $room) {
        $newRoom = Room::create($room);

        return $newRoom;
    }

    public function updateRoom(Room $room, array $roomUpdate) {
        $updatedRoom = $room->update($roomUpdate);

        return $updatedRoom;
    }
}