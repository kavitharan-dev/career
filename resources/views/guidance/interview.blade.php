@extends('layouts.app')
@section('title', 'Interview Prep — '.$career->name)

@section('page')
<p><a href="{{ route('guidance.show', $career) }}">← Back to {{ $career->name }}</a></p>
<h1 class="page-title">Interview prep — {{ $career->name }}</h1>
<p class="page-subtitle">Common interview questions and tips to prepare your answers.</p>

<div class="box">
    @foreach ($questions as $index => $item)
        <div style="padding: 16px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
            <p style="font-weight: bold; margin: 0 0 8px;">Q{{ $index + 1 }}. {{ $item['question'] }}</p>
            <p class="text-muted" style="margin: 0;"><strong>Tip:</strong> {{ $item['tip'] }}</p>
        </div>
    @endforeach
</div>

<p style="margin-top: 16px;">
    <a href="{{ route('guidance.jobs') }}" class="btn btn-teal">View Sri Lanka jobs</a>
    <a href="{{ route('guidance.resources') }}" class="btn btn-outline" style="margin-left: 8px;">Learning resources</a>
</p>
@endsection
