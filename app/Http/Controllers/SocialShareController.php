<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class SocialShareController extends Controller
{
    public function index($hash) {
        $card = Card::where('hash', $hash)->first();

        if ($card) {
            return view('share', compact('card'));
        } else {
           abort(404);
        }
    }
}
