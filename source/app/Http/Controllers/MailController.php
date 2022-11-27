<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Mail;

class MailController extends Controller
{
    public function send(){
        $name ="Tawhidul";
        Mail::to("tawhid99@gmail.com")->send(new TestEmail($name));
    }
}