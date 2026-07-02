@extends('layouts.admin')
@section('admin_title', 'Job listings')
@section('admin_subtitle', 'Add Sri Lanka job openings with expiry dates. Expired jobs hide automatically from students.')

@section('admin')
<section class="adm-card adm-form adm-form-wide">
    <h2 class="adm-card-title">Post a new job</h2>
    <form method="POST" action="{{ route('admin.jobs.store') }}" class="adm-form-grid">
        @csrf
        <div class="adm-field">
            <label for="title">Job title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="adm-input" placeholder="Junior Java Developer">
            @error('title')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field">
            <label for="company">Company</label>
            <input type="text" id="company" name="company" value="{{ old('company') }}" required class="adm-input" placeholder="Virtusa / 99X / IFS">
            @error('company')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field">
            <label for="career_domain_id">Career path (optional)</label>
            <select id="career_domain_id" name="career_domain_id" class="adm-input">
                <option value="">General / all careers</option>
                @foreach ($domains as $domain)
                    <option value="{{ $domain->id }}" @selected(old('career_domain_id') == $domain->id)>{{ $domain->name }}</option>
                @endforeach
            </select>
            @error('career_domain_id')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" class="adm-input" placeholder="Colombo / hybrid / remote">
            @error('location')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field">
            <label for="apply_url">Apply link (URL)</label>
            <input type="url" id="apply_url" name="apply_url" value="{{ old('apply_url') }}" class="adm-input" placeholder="https://topjobs.lk/...">
            @error('apply_url')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field">
            <label for="expires_at">Expires on</label>
            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required min="{{ now()->toDateString() }}" class="adm-input">
            @error('expires_at')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field adm-field-full">
            <label for="apply_note">Apply instructions (optional)</label>
            <textarea id="apply_note" name="apply_note" rows="2" class="adm-input" placeholder="Apply via topjobs.lk or company careers page">{{ old('apply_note') }}</textarea>
            @error('apply_note')<p class="adm-field-error">{{ $message }}</p>@enderror
        </div>
        <div class="adm-field adm-field-full">
            <button type="submit" class="adm-btn adm-btn-primary">Publish job</button>
        </div>
    </form>
</section>

<section class="adm-card">
    <div class="adm-card-head">
        <h2 class="adm-card-title">Active jobs ({{ $activeJobs->count() }})</h2>
        <p class="adm-muted">Visible on student dashboard until expiry date.</p>
    </div>
    @if ($activeJobs->isEmpty())
        <p class="adm-muted">No active jobs yet. Post one above.</p>
    @else
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Career</th>
                        <th>Expires</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeJobs as $job)
                        <tr>
                            <td><strong>{{ $job->title }}</strong></td>
                            <td>{{ $job->company }}</td>
                            <td>{{ $job->careerDomain?->name ?? 'General' }}</td>
                            <td><span class="adm-badge adm-badge-ok">{{ $job->expires_at->format('d M Y') }}</span></td>
                            <td class="adm-table-actions">
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="adm-link">Edit</a>
                                <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" style="display:inline;" onsubmit="return confirm('Remove this job listing?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adm-link" style="color:#dc2626;border:none;background:none;cursor:pointer;padding:0;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@if ($expiredJobs->isNotEmpty())
<section class="adm-card">
    <h2 class="adm-card-title">Recently expired</h2>
    <p class="adm-muted">Hidden from students automatically.</p>
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Expired</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expiredJobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->company }}</td>
                        <td><span class="adm-badge adm-badge-warn">{{ $job->expires_at->format('d M Y') }}</span></td>
                        <td>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="adm-link">Edit / re-publish</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif
@endsection
