<?php

namespace Tests\Feature;

use App\ContactSubmission;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactSubmissionControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_create_a_contact_submission_entry()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = $faker->email;
        $message = $faker->text();
        $type = 'support';

        $this->post('/contact', [
            'name' => $name,
            'email' => $email,
            'type' => $type,
            'message' => $message
        ]);

        /** @var ContactSubmission $contactSubmission */
        $contactSubmission = ContactSubmission::query()->where('name', '=', $name)->where('email', '=', $email)->first();
        $this->assertNotNull($contactSubmission);

        $this->assertEquals($name, $contactSubmission->name);
        $this->assertEquals($email, $contactSubmission->email);
        $this->assertEquals($type, $contactSubmission->type);
        $this->assertEquals($message, $contactSubmission->message);
    }

    /**
     * @test
     * @param $type
     * @dataProvider provides_it_will_show_the_create_contact_submission_page
     */
    public function it_will_show_the_create_contact_submission_page($type)
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/contact?=' . $type);
        $response->assertStatus(200);
    }

    public function provides_it_will_show_the_create_contact_submission_page()
    {
        return [
            'contact' => [
                'type' => 'contact'
            ],
            'support' => [
                'type' => 'support'
            ]
        ];
    }
}
