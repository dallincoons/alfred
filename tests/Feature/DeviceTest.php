<?php

class DeviceTest extends \Tests\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_transfers_control_to_room_device()
    {
        $room = factory(\App\Room::class)->create();
        $room->songs()->create([
            'external_id' => '4F21WduCSAShaMDuVcQQrE'
        ]);

        $response = $this->put('room/' . $room->getKey() . '/device/123/play');
        $response->assertSuccessful();
        $this->assertTrue($response->decodeResponseJson());
    }
}
