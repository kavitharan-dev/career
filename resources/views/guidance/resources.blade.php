@extends('layouts.app')
@section('title', 'Learning Resources')

@section('page')
@include('partials.dash-header', [
    'title' => 'Learning resources',
    'subtitle' => 'Free tutorials, docs, and practice sites for each career path.',
])

@foreach ($careers as $career)
    @php $items = $resourcesBySlug[$career->slug] ?? [] @endphp
    @if (count($items))
        <section class="dash-card" id="{{ $career->slug }}">
            <h2 class="section-title">{{ $career->name }}</h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($items as $resource)
                    <li style="padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span class="badge-pill" style="background: #ede9fe; color: #5b21b6; padding: 2px 8px; border-radius: 999px; font-size: 12px;">{{ $resource['type'] }}</span>
                        <a href="{{ $resource['url'] }}" target="_blank" rel="noopener" style="margin-left: 8px; font-weight: 600;">{{ $resource['title'] }}</a>
                    </li>
                @endforeach
            </ul>
            <p style="margin-top: 12px;">
                <a href="{{ route('guidance.show', $career) }}">Career profile</a> ·
                <a href="{{ route('roadmap') }}">Your roadmap</a>
            </p>
        </section>
    @endif
@endforeach
@endsection
