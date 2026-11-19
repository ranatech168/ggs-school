@extends('admin.layout')
@section('title', 'Live Chat Support')

@section('content')
<style>
.chat-container {
    height: calc(100vh - 170px);
    min-height: 600px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
    display: flex;
}
.chat-sidebar {
    width: 360px;
    border-right: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    background: #fafbfc;
}
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    position: relative;
}
.chat-conversation-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f3f5;
    cursor: pointer;
    transition: all 0.15s ease;
}
.chat-conversation-item:hover {
    background: #f1f5f9;
}
.chat-conversation-item.active {
    background: #e7f1ff;
    border-left: 4px solid #0B2240;
}
.chat-message-list {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.message-bubble {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 0.92rem;
    line-height: 1.45;
    position: relative;
    word-break: break-word;
    white-space: pre-line;
}
.message-visitor {
    align-self: flex-start;
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}
.message-staff {
    align-self: flex-end;
    background: #0B2240;
    color: #ffffff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 4px rgba(11,34,64,0.15);
}
.message-bot {
    align-self: flex-start;
    background: #f1f5f9;
    color: #334155;
    border-left: 3px solid #8b5cf6;
    border-bottom-left-radius: 4px;
}
.badge-bot {
    background: #ede9fe;
    color: #6d28d9;
    font-size: 0.72rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
}
.badge-visitor {
    background: #e2e8f0;
    color: #475569;
    font-size: 0.72rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
}
.badge-staff {
    background: #f5a623;
    color: #0b2240;
    font-size: 0.72rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 700;
}
.chat-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #94a3b8;
    text-align: center;
    padding: 40px;
}
</style>

<div class="chat-container">
    <!-- LEFT SIDEBAR: CONVERSATION LIST -->
    <div class="chat-sidebar">
        <div class="p-3 border-bottom bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-comments text-primary"></i> Live Chat Inquiries
                </h6>
                <span class="badge bg-danger rounded-pill" id="total-unread-badge" style="display: none;">0 unread</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="search-input" class="form-control bg-light border-start-0" placeholder="Search visitors...">
            </div>
            <div class="btn-group btn-group-sm w-100" role="group">
                <button type="button" class="btn btn-outline-secondary active filter-btn" data-status="all">All</button>
                <button type="button" class="btn btn-outline-secondary filter-btn" data-status="active">Active</button>
                <button type="button" class="btn btn-outline-secondary filter-btn" data-status="closed">Closed</button>
            </div>
        </div>

        <div id="conversation-list" class="flex-grow-1 overflow-y-auto">
            <div class="p-4 text-center text-muted small">
                <i class="fa-solid fa-spinner fa-spin me-1"></i> Loading conversations...
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: ACTIVE CONVERSATION MESSAGES & REPLY COMPOSER -->
    <div class="chat-main" id="chat-main-panel">
        <div id="no-chat-selected" class="chat-empty-state">
            <i class="fa-regular fa-comment-dots fa-3x mb-3 text-secondary" style="opacity: 0.5;"></i>
            <h5 class="fw-semibold text-secondary">No Conversation Selected</h5>
            <p class="small text-muted mb-0">Select a visitor from the left to start live chatting or viewing history.</p>
        </div>

        <div id="chat-active-view" class="d-flex flex-column h-100 d-none">
            <!-- Active Header -->
            <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-weight: bold; font-size: 1.1rem;" id="active-avatar">
                        V
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold" id="active-visitor-name">Visitor</h6>
                            <span class="badge" id="active-status-badge">Active</span>
                        </div>
                        <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                            <a href="#" id="active-visitor-phone-link" class="text-primary fw-semibold text-decoration-none">
                                <i class="fa-solid fa-phone me-1"></i><span id="active-visitor-phone">No phone</span>
                            </a>
                            <span>•</span>
                            <span id="active-visitor-meta">Session: -</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-toggle-status" title="Toggle active/closed">
                        <i class="fa-solid fa-check-circle me-1"></i> <span id="btn-status-text">Close Chat</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btn-delete-conversation" title="Delete conversation">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Thread -->
            <div class="chat-message-list" id="chat-messages-container">
                <!-- Dynamically populated -->
            </div>

            <!-- Reply Composer -->
            <div class="p-3 border-top bg-white">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="small text-muted fw-semibold"><i class="fa-solid fa-bolt text-warning me-1"></i> Quick replies:</span>
                    <select class="form-select form-select-sm w-auto" id="quick-reply-select">
                        <option value="">Select a template...</option>
                        <option value="Hello! Thank you for reaching out to Global Generation School. How may we assist you today?">Greeting</option>
                        <option value="Our admissions for the 2026-2027 academic year are open! Would you like to schedule a campus tour?">Admissions</option>
                        <option value="Tuition fees depend on grade level. May we have your phone number or email so an admissions counselor can send the exact fee schedule?">Tuition / Fees</option>
                        <option value="Our main campus is located in Lomdab Thmey, Svay Dangkum, Siem Reap. You are welcome to visit Monday to Saturday, 7:30 AM to 5:00 PM.">Campus / Hours</option>
                        <option value="Thank you! We will look into this and get back to you shortly. Have a wonderful day!">Closing</option>
                    </select>
                </div>
                <form id="reply-form" class="d-flex gap-2">
                    <textarea class="form-control" id="reply-input" rows="2" placeholder="Type your reply as {{ auth()->user()->name }}... (Press Enter to send, Shift+Enter for new line)" required></textarea>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center justify-content-center" id="btn-send-reply">
                        <i class="fa-solid fa-paper-plane fa-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    let currentConversationId = null;
    let currentFilterStatus = 'all';
    let conversationsData = [];
    let pollInterval = null;
    let messagePollInterval = null;

    // Web Audio chime for new incoming messages
    function playNotificationSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1); // A5
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.35);
        } catch(e) {}
    }

    // Load Conversations List
    function fetchConversations(quiet = false) {
        $.getJSON("{{ route('admin.chat.conversations') }}", { status: currentFilterStatus }, function(res) {
            conversationsData = res.conversations || [];
            
            // Update total unread badge
            if (res.total_unread > 0) {
                $('#total-unread-badge').text(res.total_unread + ' unread').show();
            } else {
                $('#total-unread-badge').hide();
            }

            renderConversationsList();
        });
    }

    function renderConversationsList() {
        const query = $('#search-input').val().toLowerCase().trim();
        const filtered = conversationsData.filter(c => {
            if (!query) return true;
            return (c.visitor_name && c.visitor_name.toLowerCase().includes(query)) ||
                   (c.last_message && c.last_message.toLowerCase().includes(query)) ||
                   (c.session_id && c.session_id.toLowerCase().includes(query));
        });

        if (filtered.length === 0) {
            $('#conversation-list').html('<div class="p-4 text-center text-muted small">No conversations found.</div>');
            return;
        }

        let html = '';
        filtered.forEach(c => {
            const isActive = c.id === currentConversationId ? 'active' : '';
            const unreadBadge = c.unread_count > 0 
                ? `<span class="badge bg-danger rounded-pill">${c.unread_count}</span>` 
                : '';
            const statusIndicator = c.status === 'active' 
                ? '<span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.68rem;">Active</span>' 
                : '<span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Closed</span>';

            html += `
                <div class="chat-conversation-item ${isActive}" data-id="${c.id}">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <strong class="text-truncate text-dark" style="max-width: 170px;">${escapeHtml(c.visitor_name || 'Visitor')}</strong>
                        <span class="small text-muted" style="font-size: 0.72rem;">${c.last_message_time || ''}</span>
                    </div>
                    <div class="small text-primary mb-1 d-flex align-items-center gap-1" style="font-size: 0.76rem;">
                        <i class="fa-solid fa-phone" style="font-size: 0.68rem;"></i>
                        <span class="fw-semibold">${escapeHtml(c.visitor_phone || 'No phone')}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-secondary text-truncate me-2" style="max-width: 220px;">
                            ${escapeHtml(c.last_message || 'No messages')}
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            ${statusIndicator}
                            ${unreadBadge}
                        </div>
                    </div>
                </div>
            `;
        });

        $('#conversation-list').html(html);
    }

    // Select and load conversation
    $(document).on('click', '.chat-conversation-item', function() {
        const id = $(this).data('id');
        selectConversation(id);
    });

    function selectConversation(id) {
        currentConversationId = id;
        $('.chat-conversation-item').removeClass('active');
        $(`.chat-conversation-item[data-id="${id}"]`).addClass('active');

        $('#no-chat-selected').addClass('d-none');
        $('#chat-active-view').removeClass('d-none');

        fetchMessages(true);
    }

    function fetchMessages(scrollToBottom = false) {
        if (!currentConversationId) return;

        $.getJSON("{{ url('admin/chat/conversations') }}/" + currentConversationId, function(res) {
            const c = res.conversation;
            const messages = res.messages || [];

            // Update header
            $('#active-visitor-name').text(c.visitor_name || 'Visitor');
            $('#active-avatar').text((c.visitor_name || 'V').substring(0, 1).toUpperCase());
            $('#active-visitor-meta').text('Session: ' + c.session_id.substring(0, 8) + ' • Started ' + c.created_at);
            
            if (c.visitor_phone) {
                $('#active-visitor-phone').text(c.visitor_phone);
                $('#active-visitor-phone-link').attr('href', 'tel:' + c.visitor_phone).show();
            } else {
                $('#active-visitor-phone').text('No phone');
                $('#active-visitor-phone-link').removeAttr('href');
            }
            
            if (c.status === 'active') {
                $('#active-status-badge').removeClass('bg-secondary').addClass('bg-success').text('Active');
                $('#btn-status-text').text('Close Chat');
            } else {
                $('#active-status-badge').removeClass('bg-success').addClass('bg-secondary').text('Closed');
                $('#btn-status-text').text('Reopen Chat');
            }

            // Render messages
            let html = '';
            messages.forEach(m => {
                if (m.sender_type === 'visitor') {
                    html += `
                        <div class="message-bubble message-visitor">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge-visitor"><i class="fa-solid fa-user me-1"></i> ${escapeHtml(m.sender_name)}</span>
                                <span class="text-muted" style="font-size: 0.72rem;">${m.created_at}</span>
                            </div>
                            <div>${escapeHtml(m.message)}</div>
                        </div>
                    `;
                } else if (m.sender_type === 'staff') {
                    html += `
                        <div class="message-bubble message-staff">
                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                                <span class="text-white-50" style="font-size: 0.72rem;">${m.created_at}</span>
                                <span class="badge-staff"><i class="fa-solid fa-shield-halved me-1"></i> ${escapeHtml(m.sender_name)}</span>
                            </div>
                            <div>${escapeHtml(m.message)}</div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="message-bubble message-bot">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge-bot"><i class="fa-solid fa-robot me-1"></i> ${escapeHtml(m.sender_name)} (Automated Bot)</span>
                                <span class="text-muted" style="font-size: 0.72rem;">${m.created_at}</span>
                            </div>
                            <div>${escapeHtml(m.message)}</div>
                        </div>
                    `;
                }
            });

            const container = $('#chat-messages-container');
            const prevCount = container.children('.message-bubble').length;
            container.html(html);

            if (scrollToBottom || messages.length > prevCount) {
                container.scrollTop(container[0].scrollHeight);
                if (messages.length > prevCount && prevCount > 0) {
                    playNotificationSound();
                }
            }
        });
    }

    // Reply Form Submission
    $('#reply-form').on('submit', function(e) {
        e.preventDefault();
        const input = $('#reply-input');
        const text = input.val().trim();
        if (!text || !currentConversationId) return;

        $('#btn-send-reply').prop('disabled', true);
        $.post("{{ url('admin/chat/conversations') }}/" + currentConversationId + "/reply", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            message: text
        }, function(res) {
            input.val('');
            $('#quick-reply-select').val('');
            fetchMessages(true);
            fetchConversations(true);
        }).fail(function(err) {
            toastr.error('Failed to send reply. Please try again.');
        }).always(function() {
            $('#btn-send-reply').prop('disabled', false);
            input.focus();
        });
    });

    // Enter to send (Shift+Enter for newline)
    $('#reply-input').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#reply-form').submit();
        }
    });

    // Quick replies insert
    $('#quick-reply-select').on('change', function() {
        const val = $(this).val();
        if (val) {
            $('#reply-input').val(val).focus();
        }
    });

    // Toggle Status
    $('#btn-toggle-status').on('click', function() {
        if (!currentConversationId) return;
        $.post("{{ url('admin/chat/conversations') }}/" + currentConversationId + "/toggle-status", {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(res) {
            toastr.success(res.message);
            fetchMessages();
            fetchConversations(true);
        });
    });

    // Delete Conversation
    $('#btn-delete-conversation').on('click', function() {
        if (!currentConversationId) return;
        Swal.fire({
            title: 'Delete this conversation?',
            text: 'This will permanently remove all messages in this chat history.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/chat/conversations') }}/" + currentConversationId,
                    type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(res) {
                        toastr.success(res.message);
                        currentConversationId = null;
                        $('#chat-active-view').addClass('d-none');
                        $('#no-chat-selected').removeClass('d-none');
                        fetchConversations();
                    }
                });
            }
        });
    });

    // Filter Buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilterStatus = $(this).data('status');
        fetchConversations();
    });

    // Search input
    $('#search-input').on('input', function() {
        renderConversationsList();
    });

    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }

    // Polling setup: update conversation list every 4 seconds, messages every 3 seconds
    fetchConversations();
    pollInterval = setInterval(fetchConversations, 4000);
    messagePollInterval = setInterval(function() {
        if (currentConversationId) {
            fetchMessages(false);
        }
    }, 3000);
});
</script>
@endpush
@endsection

