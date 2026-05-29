<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Frontend;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function contact() // :GET
    {
        $contact = Frontend::where('data_key', 'contact_us.content')->first();
        return view('frontend.pages.contact', compact('contact'));
    }

    public function about() // :GET
    {
        // $contact = Frontend::where('data_key', 'contact_us.content')->first();
        return view('frontend.pages.about');
    }

    public function policy($slug)
    {
        $policy = Frontend::where('data_key', 'policy.element')
            ->where('status', 1)
            ->whereJsonContains('data_value->slug', $slug)
            ->first();

        return view('frontend.pages.policy.' . strtr($slug, "-", "_"), ['policy' => $policy]);
    }

    public function faqs() // :GET
    {
        return view('frontend.pages.faqs');
    }
}
