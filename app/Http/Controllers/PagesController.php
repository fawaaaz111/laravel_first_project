<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $title = "Welcomde to ProgrammingKnowlege";
        return view('pages.index', compact('title'));
        // return 'index';
    }

    public function about() {
        // return view('pages.about');
        $title = "About Us";
        return view('pages.about')->with('title', $title);
    }

    public function services() {
        // return view('pages.services');
        $title = array(
            "title" => "Services",
            'services' => ['Web Design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($title);
    }
}