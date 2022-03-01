<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Card;

class LexiconController extends Controller
{
    private function get_card($card)
    {
        $model = $card->toArray();
        $model['image'] = cdn(
            'images/cards/' . $card->id . '/card.jpg'
        );
        return collect(['card' => $model]);
    }

    public function get_card_api(Card $card)
    {
        $data = $this->get_card($card);
        return response()->json($data);
    }

    public function get_card_web(Card $card, Request $request)
    {
        $data = $this->get_card($card);
        $data = $this->add_meta_data($data, $request);
        return view('app', ['data' => $data]);
    }

    public function get_home_web(Request $request)
    {
        $data = collect();
        $data = $this->add_meta_data($data, $request);
        return view('app', ['data' => $data]);
    }

    public function get_home_api()
    {
        return response()->json([]);
    }

    private function get_cards()
    {
        $resourceOptions = $this->parse_resource_options();

        //var_dump($resourceOptions); die();

        $query = Card::query();
        $this->apply_resource_options($query, $resourceOptions);
        $cards = $query->get()->toArray();

        foreach ($cards as $key=>$value) {
            $cards[$key]['image'] = cdn(
                'images/cards/' . $cards[$key]['id'] . '/card.jpg'
            );
        }
        
        return collect(['cards' => $cards]);
    }

    public function get_cards_web(Request $request)
    {
        $data = $this->get_cards();
        $data = $this->add_meta_data($data, $request);
        return view('app', ['data' => $data]);
    }

    public function get_cards_api()
    {
        $data = $this->get_cards();
        return response()->json($data);
    }
}