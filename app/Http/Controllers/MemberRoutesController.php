<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberRoutesController extends Controller
{
    public function membersHome () {
        return view('member.home');
    }
    
    public function membersContact () {
        return view('member.kontak');
    }

    // Tutorial Pages
    public function membersTutorial () {
        return view('member.tutorial');
    }

    public function tutorialsLine () {
        return view(('member.tutorial.line'));
    }
    public function tutorialsRelieveParallel () {
        return view(('member.tutorial.relieve_parallel'));
    }
    public function tutorialRelieveLine () {
        return view(('member.tutorial.relieve_line'));
    }
    // End Tutorial Pages
}
