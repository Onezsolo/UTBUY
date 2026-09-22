<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\SupportLink;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $links = SupportLink::active()->orderBy('type')->orderBy('sort_order')->get();

        $grouped = [
            'whatsapp_group' => $links->where('type', 'whatsapp_group'),
            'social' => $links->where('type', 'social'),
            'other' => $links->where('type', 'other'),
        ];

        return view('shop.support', compact('grouped'));
    }
}
