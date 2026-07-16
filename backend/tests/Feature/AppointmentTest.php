<?php

namespace Tests\Feature;

use App\Enums\TimeSlot;
use Carbon\Carbon;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    private array $validPayload;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-07-15 10:00:00'));

        $this->validPayload = [
            'name' => 'La Catrina',
            'email' => 'catrina@example.com',
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time_slot' => '10:00',
        ];
    }

    // ── Booking ──────────────────────────────────────────────

    public function test_book_appointment_successfully(): void
    {
        $response = $this->postJson('/api/appointments', $this->validPayload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'date', 'time_slot', 'status', 'created_at', 'updated_at'],
            ])
            ->assertJson([
                'data' => [
                    'name' => 'La Catrina',
                    'email' => 'catrina@example.com',
                    'time_slot' => '10:00',
                    'status' => 'active',
                ],
            ]);

        $this->assertDatabaseHas('appointments', [
            'email' => 'catrina@example.com',
            'time_slot' => '10:00',
            'status' => 'active',
        ]);
    }

    public function test_rejects_missing_required_fields(): void
    {
        $response = $this->postJson('/api/appointments', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'date', 'time_slot']);
    }

    public function test_rejects_invalid_email(): void
    {
        $payload = [...$this->validPayload, 'email' => 'not-an-email'];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_rejects_weekend_date(): void
    {
        $saturday = Carbon::now()->next(Carbon::SATURDAY)->format('Y-m-d');
        $payload = [...$this->validPayload, 'date' => $saturday];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['date']);
    }

    public function test_rejects_past_date(): void
    {
        $payload = [...$this->validPayload, 'date' => Carbon::yesterday()->format('Y-m-d')];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['date']);
    }

    public function test_allows_today_date(): void
    {
        $payload = [...$this->validPayload, 'date' => Carbon::today()->format('Y-m-d')];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(201);
    }

    public function test_rejects_invalid_time_slot(): void
    {
        $payload = [...$this->validPayload, 'time_slot' => '19:00'];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['time_slot']);
    }

    public function test_rejects_duplicate_slot(): void
    {
        $this->postJson('/api/appointments', $this->validPayload)->assertStatus(201);

        $response = $this->postJson('/api/appointments', $this->validPayload);

        $response->assertStatus(409)
            ->assertJson(['message' => trans('messages.slot_booked')]);
    }

    public function test_rejects_duplicate_email(): void
    {
        $this->postJson('/api/appointments', $this->validPayload)->assertStatus(201);

        $payload = [...$this->validPayload, 'time_slot' => '14:00'];
        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(409)
            ->assertJson(['message' => trans('messages.email_active')]);
    }

    // ── Slots ────────────────────────────────────────────────

    public function test_get_slots_for_date(): void
    {
        $date = Carbon::tomorrow();

        $response = $this->getJson("/api/slots/{$date->format('Y-m-d')}");

        $response->assertOk()
            ->assertJsonCount(count(TimeSlot::all()), 'data')
            ->assertJsonStructure([
                'data' => [['time', 'available']],
            ]);

        $slots = $response->json('data');
        $this->assertTrue(collect($slots)->every(fn ($s) => $s['available'] === true));
    }

    public function test_get_slots_shows_booked(): void
    {
        $date = Carbon::tomorrow();

        $this->postJson('/api/appointments', [
            ...$this->validPayload,
            'date' => $date->format('Y-m-d'),
        ])->assertStatus(201);

        $response = $this->getJson("/api/slots/{$date->format('Y-m-d')}");

        $response->assertOk();

        $booked = collect($response->json('data'))->firstWhere('time', '10:00');
        $this->assertFalse($booked['available']);

        $available = collect($response->json('data'))->firstWhere('time', '09:00');
        $this->assertTrue($available['available']);
    }

    public function test_get_month_slots(): void
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $response = $this->getJson("/api/slots/month/{$year}/{$month}");

        $response->assertOk()
            ->assertJsonStructure(['data']);

        $data = $response->json('data');
        $this->assertIsArray($data);

        foreach ($data as $dateKey => $day) {
            $this->assertArrayHasKey('available', $day);
            $this->assertArrayHasKey('total', $day);
            $this->assertEquals(count(TimeSlot::all()), $day['total']);
        }
    }
}
