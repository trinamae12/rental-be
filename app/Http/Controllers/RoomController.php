<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = $this->roomService->fetchAllRooms();

        if (!$rooms['success']) {
            return response()->json([
                'message' => $rooms['message']
            ], 401);
        }

        return response()->json([
            'success' => $rooms['success'],
            'rooms' => $rooms['rooms']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'room_name' => 'required',
            'room_month_price' => 'required | decimal:2'
        ]);

        $result = $this->roomService->createRoom($request->only('room_name', 'room_description', 'room_month_price'));

        return response()->json([
            'success' => 'true',
            'message' => 'Room created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_name' => 'required',
            'room_month_price' => 'required | decimal:2'
        ]);

        $result = $this->roomService->updateRoom($room, $request->only('room_name', 'room_description', 'room_month_price'));

        return response()->json([
            'success' => 'true',
            'message' => 'Room updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $this->roomService->setRoomToInactive($room);

        return response()->json([
            'success' => 'true',
            'message' => 'Room deleted successfully'
        ]);
    }
}
