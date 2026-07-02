@extends('layouts.app')
@section('title', 'Sri Lanka Jobs')

@section('page')
@include('partials.dash-header', [
    'title' => 'Job opportunities (Sri Lanka)',
    'subtitle' => 'Admin-curated openings with real apply links. Listings disappear after the expiry date.',
])

@if ($jobs->isEmpty())
    <section class="dash-card">
        <p class="dash-list-empty">No active job listings right now. Check back soon — new openings are added by the admin team.</p>
    </section>
@else
    <div class="dash-job-grid dash-job-grid-page">
        @foreach ($jobs as $job)
            <article class="dash-job-card">
                <div class="dash-job-top">
                    <h3>{{ $job->title }}</h3>
                    <span class="dash-job-expiry">Until {{ $job->expires_at->format('d M Y') }}</span>
                </div>
                <p class="dash-job-company">{{ $job->company }}</p>
                @if ($job->location)
                    <p class="dash-job-meta">{{ $job->location }}</p>
                @endif
                @if ($job->careerDomain)
                    <p class="dash-job-tag">{{ $job->careerDomain->name }}</p>
                @endif
                @if ($job->apply_url)
                    <a href="{{ $job->apply_url }}" target="_blank" rel="noopener" class="dash-job-apply">Apply now →</a>
                @elseif ($job->apply_note)
                    <p class="dash-job-note">{{ $job->apply_note }}</p>
                @endif
            </article>
        @endforeach
    </div>
@endif
@endsection
