<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(): Factory|View
    {
        return view('pages.wishlist');
    }
}
