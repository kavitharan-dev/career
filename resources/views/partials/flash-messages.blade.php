@if (session('success'))
    <div class="msg msg-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="msg msg-error">{{ session('error') }}</div>
@endif
