<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'solicitante_id' => User::factory(),
            'responsavel_id' => null,
            'titulo' => fake()->sentence(),
            'descricao' => fake()->paragraph(3),
            'status' => 'ABERTO',
            'prioridade' => 'MEDIA',
            'resolved_at' => null,
            'active' => true,
        ];
    }

    /**
     * Indicate that the ticket is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'RESOLVIDO',
            'resolved_at' => now(),
        ]);
    }

    /**
     * Indicate that the ticket is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate the ticket has a responsavel.
     */
    public function withResponsavel(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'responsavel_id' => $user?->id ?? User::factory(),
        ]);
    }
}
