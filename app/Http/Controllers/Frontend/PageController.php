<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function contact(): View
    {
        $contact = Frontend::query()
            ->where('data_key', Frontend::CONTACT)
            ->active()
            ->first();

        return view('frontend.contact.index', compact('contact'));
    }

    public function about(): View
    {
        return view('frontend.pages.about');
    }

    public function policy(string $slug): View
    {
        $policy = Frontend::query()
            ->where('data_key', 'Frontend::POLICY')
            ->active()
            ->whereJsonContains(
                'data_value->slug',
                $slug
            )
            ->firstOrFail();

        $view = 'frontend.pages.policy.' .
            str_replace('-', '_', $slug);

        abort_unless(
            view()->exists($view),
            404
        );

        return view($view, compact('policy'));
    }

    public function faqs(): View
    {
        return view('frontend.pages.faqs');
    }
}
