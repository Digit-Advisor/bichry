<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Project;

use App\Models\Entreprise;
use App\Models\ClientsEntreprise;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_projects()
    {
        $projects = Project::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('projects.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.projects.index')
            ->assertViewHas('projects');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_project()
    {
        $response = $this->get(route('projects.create'));

        $response->assertOk()->assertViewIs('app.projects.create');
    }

    /**
     * @test
     */
    public function it_stores_the_project()
    {
        $data = Project::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('projects.store'), $data);

        $this->assertDatabaseHas('projects', $data);

        $project = Project::latest('id')->first();

        $response->assertRedirect(route('projects.edit', $project));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_project()
    {
        $project = Project::factory()->create();

        $response = $this->get(route('projects.show', $project));

        $response
            ->assertOk()
            ->assertViewIs('app.projects.show')
            ->assertViewHas('project');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_project()
    {
        $project = Project::factory()->create();

        $response = $this->get(route('projects.edit', $project));

        $response
            ->assertOk()
            ->assertViewIs('app.projects.edit')
            ->assertViewHas('project');
    }

    /**
     * @test
     */
    public function it_updates_the_project()
    {
        $project = Project::factory()->create();

        $entreprise = Entreprise::factory()->create();
        $clientsEntreprise = ClientsEntreprise::factory()->create();

        $data = [
            'nom' => $this->faker->text(255),
            'description' => $this->faker->sentence(15),
            'cout' => $this->faker->randomNumber(2),
            'dead_line' => $this->faker->date,
            'entreprise_id' => $entreprise->id,
            'clients_entreprise_id' => $clientsEntreprise->id,
        ];

        $response = $this->put(route('projects.update', $project), $data);

        $data['id'] = $project->id;

        $this->assertDatabaseHas('projects', $data);

        $response->assertRedirect(route('projects.edit', $project));
    }

    /**
     * @test
     */
    public function it_deletes_the_project()
    {
        $project = Project::factory()->create();

        $response = $this->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));

        $this->assertDeleted($project);
    }
}
