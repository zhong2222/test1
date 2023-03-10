<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

// メール送信処理宣言 Mailファサード
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $inputs=request()->validate([
            'title'=>'required|max:255',
            'email'=>'required|email|max:255',
            'body'=>'required|max:1000',
        ]);
        Contact::create($inputs);

        // メール送信処理 Mailファサードのtoメソッドを使う
        Mail::to(config('mail.admin'))->send(new ContactForm($inputs));
        Mail::to($inputs['email'])->send(new ContactForm($inputs));

        return back()->with('message', 'メールを送信したのでご確認ください');
    }

}
