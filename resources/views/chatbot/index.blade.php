@extends('layouts.app')
@section('title', 'Chatbot')

@section('page')
@include('partials.dash-header', [
    'title' => 'Learning assistant',
    'subtitle' => config('services.groq.enabled') && config('services.groq.api_key')
        ? 'Groq AI tutor ('.config('services.groq.model').') — ask about career, tasks, and progress.'
        : 'Guided tutor — enable Groq in .env for real AI chat.',
])

@include('chatbot._chat', ['isAdmin' => false, 'messages' => $messages])
@endsection
