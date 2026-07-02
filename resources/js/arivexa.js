/* Navbar shadow when user scrolls */
function initNavbarScroll() {
    const nav = document.querySelector('[data-navbar]');
    if (!nav) return;

    const onScroll = () => {
        nav.classList.toggle('is-scrolled', window.scrollY > 10);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

/* Mobile menu toggle */
function initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const menu = document.querySelector('[data-nav-menu]');

    if (!toggle || !menu) {
        return;
    }

    toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(open));
    });

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menu.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
}

function initAssessment() {
    const root = document.querySelector('[data-assessment]');

    if (!root) {
        return;
    }

    const panels = [...root.querySelectorAll('[data-step]')];
    const progressBar = root.querySelector('[data-progress-bar]');
    const progressLabel = root.querySelector('[data-progress-label]');
    const backBtn = root.querySelector('[data-back]');
    const nextBtn = root.querySelector('[data-next]');
    const resultPanel = root.querySelector('[data-result]');
    const resultTitle = root.querySelector('[data-result-title]');
    const resultSummary = root.querySelector('[data-result-summary]');

    const outcomes = {
        analytical: {
            title: 'Analytical Explorer',
            summary:
                'You thrive on logic, research, and structured problem-solving. Paths like data science, engineering, finance, and research could be a strong fit.',
        },
        creative: {
            title: 'Creative Visionary',
            summary:
                'You express ideas through design, storytelling, and innovation. Consider careers in UX, media, marketing, architecture, or the arts.',
        },
        people: {
            title: 'People Champion',
            summary:
                'You energize teams and guide others. Teaching, HR, counseling, healthcare, and leadership roles may align with your strengths.',
        },
        builder: {
            title: 'Hands-On Builder',
            summary:
                'You learn by doing and shipping real outcomes. Entrepreneurship, operations, trades, and product roles may suit you well.',
        },
    };

    let currentStep = 0;
    const scores = { analytical: 0, creative: 0, people: 0, builder: 0 };

    const updateProgress = () => {
        const total = panels.length;
        const percent = Math.round(((currentStep + 1) / total) * 100);

        if (progressBar) {
            progressBar.style.width = `${percent}%`;
        }

        if (progressLabel) {
            progressLabel.textContent = `Step ${currentStep + 1} of ${total}`;
        }
    };

    const showPanel = (index) => {
        panels.forEach((panel, i) => {
            panel.classList.remove('is-active', 'is-exiting', 'is-entering', 'hidden');

            if (i === index) {
                panel.classList.add('is-active');
            } else {
                panel.classList.add('hidden');
            }
        });

        updateProgress();

        if (backBtn) {
            backBtn.disabled = currentStep === 0;
            backBtn.classList.toggle('opacity-40', currentStep === 0);
        }

        if (nextBtn) {
            const selected = panels[index]?.querySelector('.quiz-option.is-selected, .opt-card.is-selected');
            nextBtn.disabled = !selected;
            nextBtn.classList.toggle('opacity-40', !selected);
            nextBtn.textContent = currentStep === panels.length - 1 ? 'See results' : 'Continue';
        }
    };

    const getTopOutcome = () => {
        return Object.entries(scores).sort((a, b) => b[1] - a[1])[0][0];
    };

    const showResults = () => {
        const top = getTopOutcome();
        const outcome = outcomes[top];

        panels.forEach((panel) => panel.classList.add('hidden'));
        root.querySelector('[data-assessment-controls]')?.classList.add('hidden');

        if (resultPanel) {
            resultPanel.classList.remove('hidden');
            resultPanel.classList.add('anim-in');
        }

        if (resultTitle) {
            resultTitle.textContent = outcome.title;
        }

        if (resultSummary) {
            resultSummary.textContent = outcome.summary;
        }

        if (progressBar) {
            progressBar.style.width = '100%';
        }

        if (progressLabel) {
            progressLabel.textContent = 'Complete';
        }
    };

    panels.forEach((panel) => {
        panel.querySelectorAll('.quiz-option, .opt-card').forEach((card) => {
            card.addEventListener('click', () => {
                panel.querySelectorAll('.quiz-option, .opt-card').forEach((c) => c.classList.remove('is-selected'));
                card.classList.add('is-selected');

                if (nextBtn) {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-40');
                }
            });
        });
    });

    backBtn?.addEventListener('click', () => {
        if (currentStep === 0) {
            return;
        }

        currentStep -= 1;
        showPanel(currentStep);
    });

    nextBtn?.addEventListener('click', () => {
        const panel = panels[currentStep];
        const selected = panel?.querySelector('.quiz-option.is-selected, .opt-card.is-selected');
        const track = selected?.dataset.track;

        if (track && scores[track] !== undefined) {
            scores[track] += 1;
        }

        if (currentStep >= panels.length - 1) {
            showResults();
            return;
        }

        if (!prefersReducedMotion) {
            panel.classList.add('is-exiting');
            setTimeout(() => {
                currentStep += 1;
                showPanel(currentStep);
            }, 280);
        } else {
            currentStep += 1;
            showPanel(currentStep);
        }
    });

    showPanel(0);
}

function initDashSidebar() {
    const sidebar = document.getElementById('dash-sidebar');
    const toggle = document.querySelector('[data-dash-toggle]');
    const overlay = document.querySelector('[data-dash-overlay]');
    const closeBtn = document.querySelector('[data-dash-close]');

    if (!sidebar || !toggle) {
        return;
    }

    const close = () => {
        sidebar.classList.remove('is-open');
        overlay?.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const open = () => {
        sidebar.classList.add('is-open');
        overlay?.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', () => {
        if (sidebar.classList.contains('is-open')) {
            close();
        } else {
            open();
        }
    });

    overlay?.addEventListener('click', close);
    closeBtn?.addEventListener('click', close);

    sidebar.querySelectorAll('.dash-nav-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                close();
            }
        });
    });
}

function initAdminSidebar() {
    const sidebar = document.getElementById('adm-sidebar');
    const toggle = document.querySelector('[data-adm-toggle]');
    const overlay = document.querySelector('[data-adm-overlay]');
    const closeBtn = document.querySelector('[data-adm-close]');

    if (!sidebar || !toggle) {
        return;
    }

    const close = () => {
        sidebar.classList.remove('is-open');
        overlay?.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const open = () => {
        sidebar.classList.add('is-open');
        overlay?.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', () => {
        if (sidebar.classList.contains('is-open')) {
            close();
        } else {
            open();
        }
    });

    overlay?.addEventListener('click', close);
    closeBtn?.addEventListener('click', close);

    sidebar.querySelectorAll('.adm-nav-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                close();
            }
        });
    });
}

function initChatbot() {
    const form = document.getElementById('chat-form');

    if (!form) {
        return;
    }

    const box = document.getElementById('chat-messages');
    const input = document.getElementById('chat-input');
    const hint = document.getElementById('tutor-hint');
    const avatar = document.getElementById('tutor-avatar');
    const isAdmin = form.dataset.chatAdmin === '1';
    const hintDone = isAdmin
        ? 'Answer ready — ask another system question.'
        : 'Great question! Keep learning.';

    const setTutorState = (state) => {
        if (!avatar) {
            return;
        }
        avatar.classList.remove('is-thinking', 'is-talking');
        if (state) {
            avatar.classList.add(state);
        }
    };

    const append = (role, msg) => {
        const emptyHint = box?.querySelector('.chat-empty-hint');
        emptyHint?.remove();

        const wrap = document.createElement('div');
        wrap.className = 'chat-row ' + (role === 'user' ? 'chat-row-user' : 'chat-row-bot');

        if (role === 'assistant') {
            const mini = document.createElement('span');
            mini.className = 'tutor-mini-bot';
            mini.setAttribute('aria-hidden', 'true');
            wrap.appendChild(mini);
        }

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (role === 'user' ? 'chat-bubble-user' : 'chat-bubble-bot');
        bubble.textContent = msg;
        wrap.appendChild(bubble);
        box?.appendChild(wrap);

        if (box) {
            box.scrollTop = box.scrollHeight;
        }
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const text = input?.value.trim();
        if (!text) {
            return;
        }

        append('user', text);
        if (input) {
            input.value = '';
        }

        if (hint) {
            hint.textContent = 'Thinking...';
            hint.classList.add('is-pulse');
        }
        setTutorState('is-thinking');

        try {
            const res = await fetch(form.dataset.chatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: text }),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok || !data.reply) {
                throw new Error(data.message || 'Request failed');
            }

            setTutorState('is-talking');
            append('assistant', data.reply);

            if (hint) {
                hint.textContent = hintDone;
                hint.classList.remove('is-pulse');
            }

            setTimeout(() => setTutorState(''), 2000);
        } catch {
            setTutorState('');
            if (hint) {
                hint.textContent = 'Something went wrong. Try again.';
                hint.classList.remove('is-pulse');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initNavbarScroll();
    initMobileNav();
    initDashSidebar();
    initAdminSidebar();
    initAssessment();
    initChatbot();
});
