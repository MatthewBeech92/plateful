<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

     /**
     * Save sidebar expanded in session variable
     *
     */

     public function showSidebar(Request $request)
     {
        if (request('sidebarExpanded') === "true"){
            session(['sidebarExpanded' => true]);
            return json_encode(['sidebarExpanded' => true]); 
        }
        else {
            session(['sidebarExpanded' => false]);
            return json_encode(['sidebarExpanded' => false]); 
        }
     }
}
