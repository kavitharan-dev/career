@extends('layouts.admin')
@section('admin_title', 'System AI assistant')
@section('admin_subtitle', config('services.groq.enabled') && config('services.groq.api_key')
    ? 'Groq-powered admin helper — ask about students, registrations, curriculum, and platform stats.'
    : 'Admin system helper — answers use live database stats.')

@section('admin')
@include('chatbot._chat', ['isAdmin' => true, 'messages' => $messages])
@endsection
