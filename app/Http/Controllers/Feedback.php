<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Feedback extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable',
            'phone' => 'required',
            'message' => 'nullable',
            'url' => 'required|url',
        ]);

        /* Отправляем письмо ... */
        try {
            Mail::send(new \App\Mail\Feedback($data));
            return redirect()
                ->route('view', ['slug' => 'thanks']);
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return redirect()
                ->route('view', ['slug' => 'feedback-fail']);
        }
    }
}
