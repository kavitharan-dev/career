@extends('layouts.app')
@section('title', 'Notifications')

@section('page')
@include('partials.dash-header', [
    'title' => 'Notifications',
    'subtitle' => 'Reminders for tasks and career improvements.',
])

<div style="margin-bottom: 16px;">
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button type="submit" class="dash-action-btn dash-action-ghost">Mark all read</button>
    </form>
</div>

<ul class="mt-8 space-y-3">
    @forelse ($notifications as $notification)
        <li class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 {{ $notification->read_at ? 'opacity-70' : '' }}">
            <div>
                <p class="font-semibold">{{ $notification->title }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @unless ($notification->read_at)
                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm font-medium text-indigo-600">Mark read</button>
                </form>
            @endunless
        </li>
    @empty
        <li class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500">No notifications yet.</li>
    @endforelse
</ul>
<div class="mt-6">{{ $notifications->links() }}</div>
@endsection
