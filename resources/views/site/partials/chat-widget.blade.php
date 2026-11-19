<!-- GGS Live Chat & Chatbot Widget -->
<style>
#ggs-chat-button {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
#ggs-chat-button:hover {
    transform: translateY(-2px) scale(1.03);
}
#ggs-chat-window {
    position: fixed;
    bottom: 95px;
    right: 24px;
    width: 385px;
    max-width: calc(100vw - 32px);
    height: 570px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 10px 35px -5px rgba(11, 34, 64, 0.25), 0 0 0 1px rgba(11, 34, 64, 0.08);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: opacity 0.25s ease, transform 0.25s ease;
}
#ggs-chat-window.chat-hidden {
    opacity: 0;
    pointer-events: none;
    transform: translateY(20px) scale(0.95);
    display: none !important;
}
.chat-widget-header {
    background: linear-gradient(135deg, #0B2240 0%, #14355E 100%);
    color: #ffffff;
    padding: 16px 18px;
}
.chat-widget-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.chat-bubble-visitor {
    align-self: flex-end;
    background: #0B2240;
    color: #ffffff;
    border-radius: 16px 16px 2px 16px;
    padding: 10px 14px;
    max-width: 82%;
    font-size: 0.9rem;
    line-height: 1.4;
    word-break: break-word;
    white-space: pre-line;
    box-shadow: 0 2px 5px rgba(11, 34, 64, 0.15);
}
.chat-bubble-staff {
    align-self: flex-start;
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #F5A623;
    border-radius: 16px 16px 16px 2px;
    padding: 10px 14px;
    max-width: 85%;
    font-size: 0.9rem;
    line-height: 1.4;
    word-break: break-word;
    white-space: pre-line;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.chat-bubble-bot {
    align-self: flex-start;
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #8b5cf6;
    border-radius: 16px 16px 16px 2px;
    padding: 10px 14px;
    max-width: 85%;
    font-size: 0.9rem;
    line-height: 1.4;
    word-break: break-word;
    white-space: pre-line;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.quick-faq-btn {
    font-size: 0.75rem;
    padding: 4px 10px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    color: #334155;
    white-space: nowrap;
    transition: all 0.15s ease;
    cursor: pointer;
}
.quick-faq-btn:hover {
    background: #e2e8f0;
    border-color: #94a3b8;
    color: #0B2240;
}
.online-pulse {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
}
</style>

<!-- Floating Toggle Button -->
<div id="ggs-chat-button">
    <button type="button" id="btn-toggle-chat" class="flex items-center gap-2.5 px-4 py-3 bg-[#0B2240] hover:bg-[#14355E] text-white rounded-full shadow-2xl border-2 border-[#F5A623] cursor-pointer group focus:outline-none focus:ring-4 focus:ring-amber-300 transition-all duration-300" aria-label="Open School Live Chat">
        <div class="relative flex items-center justify-center">
            <i class="fa-solid fa-comments text-xl text-[#F5A623] group-hover:scale-110 transition-transform"></i>
            <span id="chat-unread-badge" class="absolute -top-2 -right-2 bg-rose-600 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow hidden">0</span>
        </div>
        <div class="hidden sm:flex flex-col text-left leading-tight">
            <span class="text-xs font-bold text-amber-300 tracking-wide uppercase">Live Chat</span>
            <span class="text-[11px] text-gray-200">Chat with Staff & Bot</span>
        </div>
    </button>
</div>

<!-- Chat Popup Window -->
<div id="ggs-chat-window" class="chat-hidden">
    <!-- Header -->
    <div class="chat-widget-header flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                <i class="fa-solid fa-graduation-cap text-[#F5A623] text-lg"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide leading-tight">Global Generation School</h4>
                <div class="flex items-center gap-1.5 text-[11px] text-slate-300">
                    <span class="online-pulse"></span>
                    <span>Staff & AI Assistant Online</span>
                </div>
            </div>
        </div>
        <button type="button" id="btn-close-chat" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none" aria-label="Close Chat">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    <!-- 1. MANDATORY VISITOR REGISTRATION SCREEN -->
    <div id="chat-registration-screen" class="flex-1 flex flex-col justify-between p-5 bg-gradient-to-b from-slate-50 to-white overflow-y-auto">
        <div>
            <div class="text-center my-3">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-[#0B2240] text-[#F5A623] flex items-center justify-center shadow-md text-2xl">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 leading-snug">Welcome to GGS Live Chat</h3>
                <p class="text-xs text-slate-500 mt-1">Please enter your Name and Phone Number to start chatting with our school team.</p>
                <p class="text-[11px] text-[#0B2240] font-medium mt-0.5">សូមបញ្ចូលឈ្មោះ និងលេខទូរស័ព្ទដើម្បីចាប់ផ្តើមជជែក</p>
            </div>

            <form id="visitor-registration-form" class="space-y-3.5 mt-4">
                <div>
                    <label for="reg-visitor-name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Your Full Name / ឈ្មោះពេញ <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" id="reg-visitor-name" class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0B2240] focus:border-transparent transition-all placeholder:text-slate-400" placeholder="e.g. Sokha Chann" required>
                    </div>
                </div>

                <div>
                    <label for="reg-visitor-phone" class="block text-xs font-semibold text-slate-700 mb-1">
                        Phone Number / លេខទូរស័ព្ទ <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </span>
                        <input type="tel" id="reg-visitor-phone" class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0B2240] focus:border-transparent transition-all placeholder:text-slate-400" placeholder="e.g. 088 6666 288" required>
                    </div>
                </div>

                <div id="reg-error-message" class="text-xs text-rose-600 font-medium hidden"></div>

                <button type="submit" id="btn-start-chat" class="w-full mt-2 py-2.5 px-4 bg-[#0B2240] hover:bg-[#14355E] text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>Start Chatting / ចាប់ផ្តើម</span>
                    <i class="fa-solid fa-arrow-right text-xs text-[#F5A623]"></i>
                </button>
            </form>
        </div>

        <div class="text-center pt-3 text-[11px] text-slate-400 border-t border-slate-100 mt-4">
            <i class="fa-solid fa-shield-halved text-emerald-600 me-1"></i> Your information is safe and private.
        </div>
    </div>

    <!-- 2. ACTIVE CHAT CONVERSATION SCREEN -->
    <div id="chat-conversation-screen" class="flex-1 flex flex-col h-full d-none" style="display: none;">
        <!-- Quick FAQ Buttons -->
        <div class="bg-slate-100/90 border-b border-slate-200 px-3 py-2 flex items-center gap-1.5 overflow-x-auto scrollbar-none text-xs">
            <span class="text-[11px] font-bold text-slate-500 whitespace-nowrap"><i class="fa-solid fa-bolt text-[#F5A623]"></i> FAQ:</span>
            <button type="button" class="quick-faq-btn" data-faq="Tuition & fees">Tuition & Fees</button>
            <button type="button" class="quick-faq-btn" data-faq="Academic curriculum & programs">Programs</button>
            <button type="button" class="quick-faq-btn" data-faq="Where is your campus location?">Location</button>
            <button type="button" class="quick-faq-btn" data-faq="How to enroll for 2026-2027?">Admissions</button>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="chat-widget-messages">
            <!-- Rendered by JS -->
        </div>

        <!-- Visitor Info Bar -->
        <div id="visitor-meta-bar" class="px-3 py-1.5 bg-amber-50 border-t border-amber-100 text-[11px] text-amber-900 flex items-center justify-between">
            <div class="flex items-center gap-1.5 truncate">
                <i class="fa-solid fa-user-check text-amber-600"></i>
                <span id="visitor-display-name" class="font-medium truncate">Chatting as: -</span>
            </div>
            <button type="button" id="btn-edit-visitor-name" class="text-amber-800 underline text-[11px] hover:text-amber-950 font-semibold cursor-pointer">Change</button>
        </div>

        <!-- Input Footer -->
        <div class="p-3 bg-white border-t border-slate-200">
            <form id="widget-send-form" class="flex items-center gap-2">
                <input type="text" id="widget-message-input" class="flex-1 text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B2240] focus:bg-white transition-all placeholder:text-slate-400" placeholder="Type your message here..." autocomplete="off" required>
                <button type="submit" id="widget-btn-send" class="w-10 h-10 rounded-xl bg-[#0B2240] hover:bg-[#14355E] text-white flex items-center justify-center shadow transition-colors disabled:opacity-50 cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    let chatSessionId = localStorage.getItem('ggs_chat_session_id');
    let visitorName = localStorage.getItem('ggs_visitor_name') || '';
    let visitorPhone = localStorage.getItem('ggs_visitor_phone') || '';
    let isChatOpen = false;
    let chatPollTimer = null;

    // Helper: UUID generator
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    if (!chatSessionId) {
        chatSessionId = generateUUID();
        localStorage.setItem('ggs_chat_session_id', chatSessionId);
    }

    function hasValidVisitorInfo() {
        return visitorName.trim() !== '' && visitorPhone.trim() !== '';
    }

    function showRegistrationScreen() {
        $('#chat-registration-screen').show();
        $('#chat-conversation-screen').hide();
        $('#reg-visitor-name').val(visitorName);
        $('#reg-visitor-phone').val(visitorPhone);
        setTimeout(function() {
            if (!visitorName) {
                $('#reg-visitor-name').focus();
            } else {
                $('#reg-visitor-phone').focus();
            }
        }, 100);
    }

    function showConversationScreen() {
        $('#chat-registration-screen').hide();
        $('#chat-conversation-screen').show().css('display', 'flex');
        updateVisitorDisplay();
        setTimeout(function() {
            $('#widget-message-input').focus();
            scrollChatToBottom();
        }, 100);
    }

    function updateVisitorDisplay() {
        if (hasValidVisitorInfo()) {
            $('#visitor-display-name').text('Chatting as: ' + visitorName + ' (' + visitorPhone + ')');
        } else {
            $('#visitor-display-name').text('Chatting as: Visitor');
        }
    }

    // Toggle Chat Window
    $('#btn-toggle-chat').on('click', function() {
        if (isChatOpen) {
            closeChat();
        } else {
            openChat();
        }
    });

    $('#btn-close-chat').on('click', function() {
        closeChat();
    });

    function openChat() {
        isChatOpen = true;
        $('#ggs-chat-window').removeClass('chat-hidden');
        $('#chat-unread-badge').addClass('hidden').text('0');

        if (hasValidVisitorInfo()) {
            showConversationScreen();
            initOrFetchChat();
            startPolling();
        } else {
            showRegistrationScreen();
        }
    }

    function closeChat() {
        isChatOpen = false;
        $('#ggs-chat-window').addClass('chat-hidden');
        stopPolling();
    }

    // Submit Registration (Name & Phone)
    $('#visitor-registration-form').on('submit', function(e) {
        e.preventDefault();
        const nameVal = $('#reg-visitor-name').val().trim();
        const phoneVal = $('#reg-visitor-phone').val().trim();

        if (!nameVal || !phoneVal) {
            $('#reg-error-message').text('Please fill in both your Name and Phone Number.').removeClass('hidden');
            return;
        }

        $('#reg-error-message').addClass('hidden');
        $('#btn-start-chat').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Starting...');

        $.post("{{ route('chat.init') }}", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            session_id: chatSessionId,
            visitor_name: nameVal,
            visitor_phone: phoneVal
        }).done(function(res) {
            visitorName = nameVal;
            visitorPhone = phoneVal;
            localStorage.setItem('ggs_visitor_name', visitorName);
            localStorage.setItem('ggs_visitor_phone', visitorPhone);

            if (res.session_id) {
                chatSessionId = res.session_id;
                localStorage.setItem('ggs_chat_session_id', chatSessionId);
            }

            renderMessages(res.messages || []);
            showConversationScreen();
            startPolling();
        }).fail(function(err) {
            let msg = err.responseJSON?.message || 'Could not connect. Please check your information and try again.';
            $('#reg-error-message').text(msg).removeClass('hidden');
        }).always(function() {
            $('#btn-start-chat').prop('disabled', false).html('<span>Start Chatting / ចាប់ផ្តើម</span><i class="fa-solid fa-arrow-right text-xs text-[#F5A623]"></i>');
        });
    });

    // Change Name / Phone button
    $('#btn-edit-visitor-name').on('click', function() {
        showRegistrationScreen();
    });

    // Quick FAQ Clicks
    $(document).on('click', '.quick-faq-btn', function() {
        let question = $(this).data('faq');
        $('#widget-message-input').val(question);
        $('#widget-send-form').submit();
    });

    // Initialize or Fetch Chat
    function initOrFetchChat() {
        if (!hasValidVisitorInfo()) return;

        $.post("{{ route('chat.init') }}", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            session_id: chatSessionId,
            visitor_name: visitorName,
            visitor_phone: visitorPhone
        }).done(function(res) {
            if (res.session_id) {
                chatSessionId = res.session_id;
                localStorage.setItem('ggs_chat_session_id', chatSessionId);
            }
            renderMessages(res.messages || []);
        });
    }

    // Render Messages
    function renderMessages(msgs) {
        const container = $('#chat-messages');
        let html = '';
        msgs.forEach(function(m) {
            if (m.sender_type === 'visitor') {
                html += `
                    <div class="chat-bubble-visitor">
                        <div class="text-[11px] text-blue-200 mb-0.5 text-right font-semibold">${escapeHtml(m.sender_name || visitorName || 'You')}</div>
                        <div>${escapeHtml(m.message)}</div>
                        <div class="text-[10px] text-blue-300/80 mt-1 text-right">${m.created_at}</div>
                    </div>
                `;
            } else if (m.sender_type === 'staff') {
                html += `
                    <div class="chat-bubble-staff">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-[#0B2240] text-[#F5A623] px-1.5 py-0.5 rounded">GGS Staff</span>
                            <span class="text-[11px] font-bold text-slate-800">${escapeHtml(m.sender_name)}</span>
                        </div>
                        <div class="text-slate-800">${escapeHtml(m.message)}</div>
                        <div class="text-[10px] text-slate-400 mt-1">${m.created_at}</div>
                    </div>
                `;
            } else {
                // Bot message
                html += `
                    <div class="chat-bubble-bot">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded"><i class="fa-solid fa-robot me-1"></i> Bot</span>
                            <span class="text-[11px] font-bold text-purple-900">${escapeHtml(m.sender_name)}</span>
                        </div>
                        <div class="text-slate-800">${escapeHtml(m.message)}</div>
                        <div class="text-[10px] text-slate-400 mt-1">${m.created_at}</div>
                    </div>
                `;
            }
        });

        const prevLen = container.children().length;
        container.html(html);

        if (msgs.length > prevLen) {
            scrollChatToBottom();
        }
    }

    function scrollChatToBottom() {
        const c = $('#chat-messages');
        if (c.length) {
            c.scrollTop(c[0].scrollHeight);
        }
    }

    // Send Message
    $('#widget-send-form').on('submit', function(e) {
        e.preventDefault();
        const input = $('#widget-message-input');
        const text = input.val().trim();
        if (!text) return;

        if (!hasValidVisitorInfo()) {
            showRegistrationScreen();
            return;
        }

        $('#widget-btn-send').prop('disabled', true);
        
        $.post("{{ route('chat.send') }}", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            session_id: chatSessionId,
            visitor_name: visitorName,
            visitor_phone: visitorPhone,
            message: text
        }).done(function(res) {
            input.val('');
            pollMessages(true);
        }).fail(function(err) {
            if (err.responseJSON?.requires_registration) {
                showRegistrationScreen();
            } else {
                alert('Could not send message. Please check your internet connection.');
            }
        }).always(function() {
            $('#widget-btn-send').prop('disabled', false);
            input.focus();
        });
    });

    // Poll messages
    function pollMessages(scroll = false) {
        if (!chatSessionId || !hasValidVisitorInfo()) return;

        $.getJSON("{{ route('chat.messages') }}", { session_id: chatSessionId }, function(res) {
            if (res && res.messages) {
                renderMessages(res.messages);
                if (scroll) {
                    scrollChatToBottom();
                }
            }
        });
    }

    function startPolling() {
        if (chatPollTimer) clearInterval(chatPollTimer);
        chatPollTimer = setInterval(function() {
            if (isChatOpen && hasValidVisitorInfo()) {
                pollMessages(false);
            }
        }, 3000);
    }

    function stopPolling() {
        if (chatPollTimer) {
            clearInterval(chatPollTimer);
            chatPollTimer = null;
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    // Initialize display state
    if (hasValidVisitorInfo()) {
        updateVisitorDisplay();
    }
})();
</script>
