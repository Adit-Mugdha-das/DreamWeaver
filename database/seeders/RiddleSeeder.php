<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Riddle;

class RiddleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $riddles = [
            ['question' => 'I speak without a mouth and hear without ears. I have no body, but I come alive with wind. What am I?', 'answer' => 'echo', 'hint' => 'It answers but never speaks.'],
            ['question' => 'What can fill a room but takes up no space?', 'answer' => 'light', 'hint' => 'It shines without substance.'],
            ['question' => 'The more you take, the more you leave behind. What am I?', 'answer' => 'footsteps', 'hint' => 'You leave these behind as you walk.'],
            ['question' => 'What has keys but can’t open locks?', 'answer' => 'keyboard', 'hint' => 'You use it to type.'],
            ['question' => 'What can travel around the world while staying in the same corner?', 'answer' => 'stamp', 'hint' => 'It sticks but goes far.'],
            ['question' => 'What comes once in a minute, twice in a moment, but never in a thousand years?', 'answer' => 'm', 'hint' => 'It’s a letter, not a word.'],
            ['question' => 'I’m tall when I’m young, and I’m short when I’m old. What am I?', 'answer' => 'candle', 'hint' => 'I melt with time.'],
            ['question' => 'What has a head, a tail, but no body?', 'answer' => 'coin', 'hint' => 'It can be flipped.'],
            ['question' => 'What has to be broken before you can use it?', 'answer' => 'egg', 'hint' => 'Found in the fridge, cracked for breakfast.'],
            ['question' => 'What gets wetter the more it dries?', 'answer' => 'towel', 'hint' => 'It helps you dry.'],
            ['question' => 'I have branches, but no fruit, trunk or leaves. What am I?', 'answer' => 'bank', 'hint' => 'It deals with money, not trees.'],
            ['question' => 'What can’t talk but will reply when spoken to?', 'answer' => 'echo', 'hint' => 'A mountain may return it.'],
            ['question' => 'I’m not alive, but I grow. I don’t have lungs, but I need air. What am I?', 'answer' => 'fire', 'hint' => 'I consume and burn.'],
            ['question' => 'What invention lets you look right through a wall?', 'answer' => 'window', 'hint' => 'Clear but protective.'],
            ['question' => 'What has hands but can’t clap?', 'answer' => 'clock', 'hint' => 'Always on time.'],
            ['question' => 'What has one eye but can’t see?', 'answer' => 'needle', 'hint' => 'You thread it.'],
            ['question' => 'The more you take from me, the bigger I get. What am I?', 'answer' => 'hole', 'hint' => 'It’s emptiness that grows.'],
            ['question' => 'What is always in front of you but can’t be seen?', 'answer' => 'future', 'hint' => 'It’s yet to come.'],
            ['question' => 'What has many teeth but can’t bite?', 'answer' => 'comb', 'hint' => 'Used on hair.'],
            ['question' => 'What has legs but doesn’t walk?', 'answer' => 'table', 'hint' => 'Furniture that stands.'],
            ['question' => 'What comes down but never goes up?', 'answer' => 'rain', 'hint' => 'Falls from the sky.'],
            ['question' => 'What has a neck but no head?', 'answer' => 'bottle', 'hint' => 'It holds liquid.'],
            ['question' => 'What is full of holes but still holds water?', 'answer' => 'sponge', 'hint' => 'Great for cleaning.'],
            ['question' => 'What goes up but never comes down?', 'answer' => 'age', 'hint' => 'It grows with time.'],
            ['question' => 'What has four wheels and flies?', 'answer' => 'garbage truck', 'hint' => 'It’s smelly and rolls.'],
            ['question' => 'What begins with T, ends with T, and has T in it?', 'answer' => 'teapot', 'hint' => 'You pour tea from it.'],
            ['question' => 'What is easy to lift but hard to throw?', 'answer' => 'feather', 'hint' => 'Light and floaty.'],
            ['question' => 'I’m found in socks, scarves and mittens; and often in the paws of playful kittens. What am I?', 'answer' => 'yarn', 'hint' => 'A favorite toy for cats.'],
            ['question' => 'I can be cracked, made, told, and played. What am I?', 'answer' => 'joke', 'hint' => 'It’s meant to be funny.'],
            ['question' => 'What building has the most stories?', 'answer' => 'library', 'hint' => 'You read here.']
        ];

        foreach ($riddles as $riddle) {
            Riddle::create($riddle);
        }
    }
}
