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
            ['question' => 'I speak without a mouth and hear without ears. I have no body, but I come alive with wind. What am I?', 'answer' => 'echo'],
            ['question' => 'What can fill a room but takes up no space?', 'answer' => 'light'],
            ['question' => 'The more you take, the more you leave behind. What am I?', 'answer' => 'footsteps'],
            ['question' => 'What has keys but can’t open locks?', 'answer' => 'keyboard'],
            ['question' => 'What can travel around the world while staying in the same corner?', 'answer' => 'stamp'],
            ['question' => 'What comes once in a minute, twice in a moment, but never in a thousand years?', 'answer' => 'm'],
            ['question' => 'I’m tall when I’m young, and I’m short when I’m old. What am I?', 'answer' => 'candle'],
            ['question' => 'What has a head, a tail, but no body?', 'answer' => 'coin'],
            ['question' => 'What has to be broken before you can use it?', 'answer' => 'egg'],
            ['question' => 'What gets wetter the more it dries?', 'answer' => 'towel'],
            ['question' => 'I have branches, but no fruit, trunk or leaves. What am I?', 'answer' => 'bank'],
            ['question' => 'What can’t talk but will reply when spoken to?', 'answer' => 'echo'],
            ['question' => 'I’m not alive, but I grow. I don’t have lungs, but I need air. What am I?', 'answer' => 'fire'],
            ['question' => 'What invention lets you look right through a wall?', 'answer' => 'window'],
            ['question' => 'What has hands but can’t clap?', 'answer' => 'clock'],
            ['question' => 'What has one eye but can’t see?', 'answer' => 'needle'],
            ['question' => 'The more you take from me, the bigger I get. What am I?', 'answer' => 'hole'],
            ['question' => 'What is always in front of you but can’t be seen?', 'answer' => 'future'],
            ['question' => 'What has many teeth but can’t bite?', 'answer' => 'comb'],
            ['question' => 'What has legs but doesn’t walk?', 'answer' => 'table'],
            ['question' => 'What comes down but never goes up?', 'answer' => 'rain'],
            ['question' => 'What has a neck but no head?', 'answer' => 'bottle'],
            ['question' => 'What is full of holes but still holds water?', 'answer' => 'sponge'],
            ['question' => 'What goes up but never comes down?', 'answer' => 'age'],
            ['question' => 'What has four wheels and flies?', 'answer' => 'garbage truck'],
            ['question' => 'What begins with T, ends with T, and has T in it?', 'answer' => 'teapot'],
            ['question' => 'What is easy to lift but hard to throw?', 'answer' => 'feather'],
            ['question' => 'I’m found in socks, scarves and mittens; and often in the paws of playful kittens. What am I?', 'answer' => 'yarn'],
            ['question' => 'I can be cracked, made, told, and played. What am I?', 'answer' => 'joke'],
            ['question' => 'What building has the most stories?', 'answer' => 'library'],
        ];

        foreach ($riddles as $riddle) {
            Riddle::create($riddle);
        }
    }
}
