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
        $response = $this->put('device/123/play');
        $response->assertSuccessful();
        $this->assertTrue($response->decodeResponseJson());
    }
}
