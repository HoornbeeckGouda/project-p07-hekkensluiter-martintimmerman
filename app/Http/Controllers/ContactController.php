<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

   // app/Http/Controllers/ContactController.php

public function handleForm(Request $request)
{
    // Valideer de gegevens
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:500',
    ]);

    // 'Nep' succesmelding - Hier sturen we geen e-mail, maar geven we een bericht weer
    return redirect()->route('contact')->with('success', 'Uw bericht is verzonden!');
}

    }

