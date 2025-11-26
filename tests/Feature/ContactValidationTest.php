<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Contact;

class ContactValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store_shows_validation_errors_for_invalid_input()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('contacts.store'), [
            'name' => 'Abc', // too short (min 6)
            'contact' => '1234', // not 9 digits
            'email' => 'invalid-email', // not an email
        ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
    }

    /** @test */
    public function update_shows_validation_errors_for_invalid_input()
    {
        $user = User::factory()->create();

        $contact = Contact::create([
            'name' => 'Valid Name',
            'contact' => '123456789',
            'email' => 'unique@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('contacts.update', $contact->id), [
            'name' => 'abc',
            'contact' => 'abcd',
            'email' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
    }
}
