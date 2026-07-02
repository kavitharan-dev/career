@extends('layouts.admin')
@section('admin_title', 'Edit job')
@section('admin_subtitle', 'Update details or extend the expiry date to show this job again.')

@section('admin')
<form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="adm-card adm-form">
    @csrf
    @method('PUT')
    <div class="adm-field">
        <label for="title">Job title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $job->title) }}" required class="adm-input">
        @error('title')<p class="adm-field-error">{{ $message }}</p>@enderror
    </div>
    <div class="adm-field">
        <label for="company">Company</label>
        <input type="text" id="company" name="company" value="{{ old('company', $job->company) }}" required class="adm-input">
        @error('company')<p class="adm-field-error">{{ $message }}</p>@enderror
    </div>
    <div class="adm-field">
        <label for="career_domain_id">Career path (optional)</label>
        <select id="career_domain_id" name="career_domain_id" class="adm-input">
            <option value="">General / all careers</option>
            @foreach ($domains as $domain)
                <option value="{{ $domain->id }}" @selected(old('career_domain_id', $job->career_domain_id) == $domain->id)>{{ $domain->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="adm-field">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="{{ old('location', $job->location) }}" class="adm-input">
    </div>
    <div class="adm-field">
        <label for="apply_url">Apply link (URL)</label>
        <input type="url" id="apply_url" name="apply_url" value="{{ old('apply_url', $job->apply_url) }}" class="adm-input">
    </div>
    <div class="adm-field">
        <label for="expires_at">Expires on</label>
        <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at', $job->expires_at->toDateString()) }}" required class="adm-input">
        @error('expires_at')<p class="adm-field-error">{{ $message }}</p>@enderror
    </div>
    <div class="adm-field adm-field-full">
        <label for="apply_note">Apply instructions (optional)</label>
        <textarea id="apply_note" name="apply_note" rows="2" class="adm-input">{{ old('apply_note', $job->apply_note) }}</textarea>
    </div>
    <div class="adm-field adm-field-full">
        <label class="adm-check">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $job->is_active))>
            <span>Active (uncheck to hide immediately)</span>
        </label>
    </div>
    <div class="adm-field adm-field-full" style="display:flex;gap:12px;flex-wrap:wrap;">
        <button type="submit" class="adm-btn adm-btn-primary">Save changes</button>
        <a href="{{ route('admin.jobs.index') }}" class="adm-btn adm-btn-ghost">Back to jobs</a>
    </div>
</form>
@endsection
