<?php

namespace Database\Factories\Link;

use App\Helpers\OpenGraph\Parser;
use App\Models\Collection\Collection;
use App\Models\Link\Link;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $url = $this->faker->randomElement([
            'https://figma.com',
            'https://yael.dev',
            'https://youtube.com',
            'https://producthunt.com',
            'https://google.com',
            'https://github.com',
            'https://twitter.com',
            'https://dev.to',
            'https://www.shopify.com',
            'https://laravel.com',
            'https://tailwindcss.com'
        ]);

        $ogp = Parser::parse($url);

        return [
            'user_id' => User::factory(),
            'collection_id' => Collection::factory(),
            'url' => $url,
            'ogp' => $ogp
        ];
    }
}
