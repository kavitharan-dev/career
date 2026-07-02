<div class="chatbot-layout dash-chatbot {{ $isAdmin ? 'chatbot-layout-admin' : '' }}">
    <aside class="tutor-panel {{ $isAdmin ? 'tutor-panel-admin' : '' }}">
        <div class="tutor-avatar" id="tutor-avatar" aria-hidden="true">
            <div class="tutor-antenna"><span class="tutor-antenna-ball"></span></div>
            <div class="tutor-robot-head">
                <div class="tutor-face-plate">
                    <span class="tutor-eye tutor-eye-l"></span>
                    <span class="tutor-eye tutor-eye-r"></span>
                    <span class="tutor-mouth"></span>
                </div>
            </div>
            <div class="tutor-robot-body"><span class="tutor-chest-light"></span></div>
            <div class="tutor-arm tutor-arm-l"></div>
            <div class="tutor-arm tutor-arm-r"></div>
        </div>
        <p class="tutor-panel-title">{{ $isAdmin ? 'Admin Assistant' : 'Arivexa Tutor' }}</p>
        <p class="tutor-panel-sub">{{ $isAdmin ? 'System & platform helper' : 'Your learning robot' }}</p>
        <p id="tutor-hint" class="tutor-hint">
            {{ $isAdmin ? 'Ask about students, onboarding, or system stats.' : 'Ask me anything about your roadmap!' }}
        </p>
        @if ($isAdmin)
            <ul class="tutor-admin-suggestions">
                <li>How many students registered?</li>
                <li>System overview</li>
                <li>Pending onboarding count</li>
                <li>Curriculum summary</li>
            </ul>
        @endif
    </aside>

    <div class="chatbot-main">
        <div id="chat-messages" class="chat-messages">
            @foreach ($messages as $msg)
                <div class="chat-row {{ $msg->role === 'user' ? 'chat-row-user' : 'chat-row-bot' }}">
                    @if ($msg->role === 'assistant')
                        <span class="tutor-mini-bot" aria-hidden="true"></span>
                    @endif
                    <div class="chat-bubble {{ $msg->role === 'user' ? 'chat-bubble-user' : 'chat-bubble-bot' }}">
                        {{ $msg->message }}
                    </div>
                </div>
            @endforeach
            @if ($messages->isEmpty())
                <p class="text-muted chat-empty-hint">
                    @if ($isAdmin)
                        Try: &quot;How many students are registered?&quot; or &quot;Give me a system overview&quot;
                    @else
                        Try: &quot;What is my next step?&quot; or &quot;Show my progress&quot;
                    @endif
                </p>
            @endif
        </div>
        <form
            id="chat-form"
            class="chat-form"
            data-chat-url="{{ route('chatbot.send') }}"
            data-chat-admin="{{ $isAdmin ? '1' : '0' }}"
        >
            <div class="chat-form-inner">
                <input
                    type="text"
                    id="chat-input"
                    name="message"
                    required
                    maxlength="1000"
                    placeholder="{{ $isAdmin ? 'Ask a system question...' : 'Type your question...' }}"
                    class="chat-input"
                    autocomplete="off"
                >
                <button type="submit" class="chat-send-btn">Send</button>
            </div>
        </form>
    </div>
</div>
