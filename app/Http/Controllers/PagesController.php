<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class PagesController extends Controller
{
    public function __construct()
    {
        
    }

    public function home(Request $request)
    {                          
        $data = array();              
        return view('welcome',$data);
    }

    public function economics(Request $request)
    {                          
        $data = array();              
        return view('economics',$data);
    }         
}
