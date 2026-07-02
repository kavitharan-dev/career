@extends('layouts.admin')
@section('admin_title', 'Students')
@section('admin_subtitle', 'All registered students, onboarding status, and roadmap progress.')

@section('admin')
<section class="adm-card">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Onboarding</th><th>Career</th><th>Progress</th></tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ route('admin.users.show', $user) }}" class="adm-link">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->hasCompletedOnboarding())
                                <span class="adm-badge adm-badge-ok">Done</span>
                            @else
                                <span class="adm-badge adm-badge-warn">Pending</span>
                            @endif
                        </td>
                        <td>{{ $user->primaryRecommendation?->careerDomain?->name ?? '—' }}</td>
                        <td><span class="adm-pill">{{ $user->activeRoadmap?->completion_percentage ?? 0 }}%</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="adm-pagination">{{ $users->links() }}</div>
</section>
@endsection
