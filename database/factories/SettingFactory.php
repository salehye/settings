<?php

namespace Salehye\Settings\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Salehye\Settings\Models\Setting;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Salehye\Settings\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Salehye\Settings\Models\Setting>
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'group' => $this->faker->randomElement(['general', 'system', 'seo', 'social', 'contact']),
            'is_public' => $this->faker->boolean(50),
            'value' => $this->faker->word(),
        ];
    }

    /**
     * Indicate that the setting is a boolean type.
     */
    public function boolean(mixed $value = null): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => $value ?? $this->faker->boolean(),
        ]);
    }

    /**
     * Indicate that the setting is a string type.
     */
    public function string(?string $value = null): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => $value ?? $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the setting is a JSON type.
     *
     * @param array<string, mixed>|null $value
     */
    public function json(?array $value = null): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => $value ?? [$this->faker->word() => $this->faker->word()],
        ]);
    }

    /**
     * Indicate that the setting is in a specific group.
     */
    public function inGroup(string $group): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => $group,
        ]);
    }

    /**
     * Indicate that the setting is public.
     */
    public function public(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the setting is private.
     */
    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }
}
