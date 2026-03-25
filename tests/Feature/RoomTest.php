<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_user_cannot_create_room()
    {
        $response = $this->postJson('/api/room',[
            'room_name' => 'Room 3',
            'room_description' => 'Next to Room 2',
            'room_month_price' => '6000.00'
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_create_room()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/room',[
            'room_name' => 'Room 3',
            'room_description' => 'Next to Room 2',
            'room_month_price' => '6000.00'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('rooms',[
            'room_name' => 'Room 3',
            'room_description' => 'Next to Room 2',
            'room_month_price' => '6000'
        ]);
    }

    public function test_no_room_name_in_create_room()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/room',[
            'room_name' => '',
            'room_description' => 'Next to Room 2',
            'room_month_price' => '6000.00'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['room_name']);
    }

    public function test_no_room_month_price_in_create_room()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/room',[
            'room_name' => 'Room 3',
            'room_description' => '',
            'room_month_price' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['room_month_price']);
    }

    public function test_room_month_price_not_in_decimal_form_in_create_room()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/room',[
            'room_name' => 'Room 3',
            'room_description' => '',
            'room_month_price' => '6000'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['room_month_price']);
    }

    public function test_update_room()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        // Create room first
        $room = Room::factory()->create();

        $response = $this->putJson("/api/room/{$room->id}", [
            'room_name' => 'Room Number 3',
            'room_description' => 'This room is next to room number 2',
            'room_month_price' => '6500.00'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('rooms', [
            'room_name' => 'Room Number 3',
            'room_description' => 'This room is next to room number 2',
            'room_month_price' => '6500.00'
        ]);
    }

    public function test_update_room_no_room_number()
    {
        /** 
         * @var \App\Models\User $user 
        */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        // Create room first
        $room = Room::factory()->create();

        $response = $this->putJson("/api/room/{$room->id}", [
            'room_name' => '',
            'room_description' => 'This room is next to room number 2',
            'room_month_price' => '6500.00'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['room_name']);
    }

    public function test_update_room_no_room_price()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        // Create a room first
        $room = Room::factory()->create();

        $response = $this->putJson("/api/room/{$room->id}",[
            'room_name' => 'Room Number 3',
            'room_description' => 'This room is next to room number 2',
            'room_month_price' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['room_month_price']);
    }

    public function test_set_room_inactive()
    {
        /**
         * @var \App\Models\User $user
         */

        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpassword2')
        ]);

        $this->actingAs($user, 'sanctum');

        // Create room
        $room = Room::factory()->create();

        // Delete room
        $room->delete();
        $this->assertSoftDeleted($room);

        // Check if deleted room is not found in find query
        $foundRoom = Room::find($room->id);
        $this->assertNull($foundRoom);

        // Check if deleted room is found if query with trashed
        $trashedRoom = Room::withTrashed()->find($room->id);
        $this->assertNotNull($trashedRoom);
    }
}
