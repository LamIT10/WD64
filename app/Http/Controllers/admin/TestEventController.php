<?php

namespace App\Http\Controllers\Admin;

use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestEventController extends Controller
{
    public function index()
    {
        return Inertia::render('TestEvent/Index');
    }

    public function broadcast(Request $request)
    {
        $message = $request->input('message', 'Hello, world!');
        event(new TestEvent($message));

        return response()->json([
            'success' => true,
            'message' => "Event sent successfully with message: $message"
        ]);
    }
}
