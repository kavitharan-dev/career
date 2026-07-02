<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    public function __construct(protected ChatbotService $chatbot) {}

    public function index(Request $request): View
    {
        $messages = $request->user()->chatMessages()->latest()->limit(50)->get()->reverse()->values();

        if ($request->user()->is_admin) {
            return view('chatbot.admin', compact('messages'));
        }

        return view('chatbot.index', compact('messages'));
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $reply = $this->chatbot->reply($request->user(), $validated['message']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'reply' => 'Sorry, something went wrong. Please try again.',
                'timestamp' => now()->format('H:i'),
            ], 500);
        }

        return response()->json([
            'reply' => $reply,
            'timestamp' => now()->format('H:i'),
        ]);
    }
}
