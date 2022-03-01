<?php

use Illuminate\Database\Seeder;
use App\Card;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path() . '/database/data.json';
        $file = File::get($path);
        $data = json_decode($file);
        foreach ($data as $obj) {
            Card::create([
                'id' => $obj->id,
                'title' => $obj->title,
                'set' => $obj->set,
                'types' => $obj->types,
                'brigades' => $obj->brigades,
                'strength' => $obj->strength,
                'toughness' => $obj->toughness,
                'class' => $obj->class,
                'special_ability' => $obj->special_ability,
                'identifiers' => $obj->identifiers,
                'reference' => $obj->reference,
                'artist' => $obj->artist,
                'rarity' => $obj->rarity,
                'play_as' => $obj->play_as,
                'errata' => $obj->errata
            ]);
        }
    }
}
