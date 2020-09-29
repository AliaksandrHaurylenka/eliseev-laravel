<?php

namespace App\Http\Controllers;


class FlashController extends Controller
{
    public function flash()
    {
       flash('Sorry! Please try again.')->error();
//      flash()->overlay('You are now a Laracasts member!', 'Yay');
      return view('home');
    }
}
