@extends('layout.master')

@section('content')
    <div class="col-span-12">
        <div class="tc-chat-app-shell">

            {{-- ── 1. LEFT PANE: CONVERSATIONS LIST ───────────────────── --}}
            <div class="tc-chat-pane-left">
                <div class="tc-chat-sidebar-header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 m-0">Messages</h2>
                        @if(auth()->user()->isInternalAdmin())
                            <button type="button"
                                    class="btn btn-primary btn-sm !py-1 !px-2.5 flex items-center gap-1 text-xs"
                                    onclick="$('#newChatCollapse').toggleClass('hidden')">
                                <i class="ti ti-plus text-sm"></i> New
                            </button>
                        @endif
                    </div>

                    {{-- Search Input --}}
                    <div class="tc-chat-sidebar-search">
                        <i class="ti ti-search"></i>
                        <input type="text" id="searchConversations" placeholder="Search conversations..." onkeyup="filterConversations()">
                    </div>
                </div>

                {{-- New Conversation Drawer Form (Internal Admins - hidden by default, 0 height) --}}
                @if(auth()->user()->isInternalAdmin())
                    <div class="hidden msg-create-panel" id="newChatCollapse">
                        <div class="msg-create-panel-header">
                            <h6>Create New Conversation</h6>
                            <button type="button" class="msg-create-close" onclick="$('#newChatCollapse').addClass('hidden')" aria-label="Close">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('api.conversations.store') }}" class="msg-create-form">
                            @csrf
                            <div class="msg-form-group">
                                <label class="msg-form-label" for="conversationName">Conversation Name <span class="text-red-500">*</span></label>
                                <input type="text" id="conversationName" name="name" class="msg-form-input" placeholder="e.g. Ticket #1234 Discussion" required>
                            </div>
                            <div class="msg-form-group">
                                <label class="msg-form-label" for="Addparticipants">Add Members <span class="text-red-500">*</span></label>
                                <select class="form-control" name="user_id[]" id="Addparticipants" multiple required></select>
                            </div>
                            <button type="submit" class="msg-submit-btn">
                                <i class="ti ti-send text-sm"></i>
                                Create Conversation
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Scrollable Conversations List (Snaps to Top) --}}
                <div class="tc-chat-conversations-list scroll-block">
                    @foreach($conversations as $conversation)
                        @php($isActive = $conversation->id === $currentConversation->id)
                        <a href="{{ route('messaging.show', $conversation->id) }}"
                           class="tc-chat-conv-item {{ $isActive ? 'active' : '' }}"
                           data-name="{{ strtolower($conversation->name) }}">
                            <div class="tc-chat-avatar-circle online">
                                {{ strtoupper(substr($conversation->name, 0, 2)) }}
                            </div>
                            <div class="grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h6 class="font-bold text-slate-900 text-xs truncate m-0">{{ $conversation->name }}</h6>
                                    <span class="text-[10.5px] text-slate-400 font-medium shrink-0 ml-1">
                                        {{ \Carbon\Carbon::parse($conversation->updated_at)->diffForHumans(null, true, true) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 truncate m-0">
                                    @if($conversation->messages->last())
                                        {{ $conversation->messages->last()->content ?: 'Attachment file' }}
                                    @else
                                        No messages yet
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ── 2. RIGHT PANE: ACTIVE CONVERSATION STREAM ───────────── --}}
            <div class="tc-chat-pane-right">

                {{-- Top Header --}}
                <div class="tc-chat-main-header">
                    <div class="flex items-center gap-3">
                        <div class="tc-chat-avatar-circle">
                            {{ strtoupper(substr($currentConversation->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 m-0 leading-tight">{{ $currentConversation->name }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active Now
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="text-[11px] text-slate-500">{{ $currentConversation->users->count() }} Participants</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm !py-1.5 !px-3 flex items-center gap-1.5 text-xs"
                                onclick="toggleChatDetails()">
                            <i class="ti ti-info-circle text-sm"></i>
                            <span>Details</span>
                        </button>
                    </div>
                </div>

                {{-- Messages Scroll Stream --}}
                <div class="tc-chat-stream-area scroll-block">
                    @foreach($currentConversation->messages as $message)
                        @if($message->sender->id === auth()->user()->id)
                            {{-- OUTGOING MESSAGE (CURRENT USER) --}}
                            <div class="tc-msg-row outgoing" data-messageid="{{ $message->id }}">
                                <div>
                                    <div class="tc-msg-bubble-out">
                                        <p class="m-0 leading-relaxed">{{ $message->content }}</p>
                                        @foreach($message->attachments as $attachment)
                                            <a href="{{ $attachment->file_path }}" download class="tc-msg-attachment-pill">
                                                <i class="ti ti-file-download text-base"></i>
                                                <span>{{ $attachment->file_name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="flex items-center justify-end gap-1.5 mt-1 text-[11px] text-slate-400">
                                        <span>{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
                                        <i class="ti ti-checks text-indigo-600 text-sm {{ !$message->isReadByAnyone() ? 'opacity-40' : '' }}" id="readIcon"></i>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- INCOMING MESSAGE (OTHER USER) --}}
                            <div class="tc-msg-row incoming" data-messageid="{{ $message->id }}">
                                <div class="tc-chat-avatar-circle shrink-0 !w-8 !h-8 !text-xs">
                                    {{ strtoupper(substr($message->sender->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 mb-1 ml-1">
                                        {{ $message->sender->name }}
                                    </div>
                                    <div class="tc-msg-bubble-in">
                                        <p class="m-0 leading-relaxed">{{ $message->content }}</p>
                                        @foreach($message->attachments as $attachment)
                                            <a href="{{ $attachment->file_path }}" download class="tc-msg-attachment-pill">
                                                <i class="ti ti-file-download text-base"></i>
                                                <span>{{ $attachment->file_name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1 ml-1">
                                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Composer Box --}}
                <div class="tc-chat-composer-wrap">
                    <div class="tc-chat-composer-box">
                        <button type="button"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                id="attachmentIcon"
                                title="Attach File">
                            <i class="ti ti-paperclip text-lg"></i>
                        </button>
                        <input type="file" id="messageAttachment" class="hidden" />

                        <textarea id="newMessageText" placeholder="Type a message... (Press Enter to send)" rows="1"></textarea>

                        <span class="hidden badge bg-indigo-100 text-indigo-700 font-semibold px-2 py-1 text-xs rounded" id="attachmentName"></span>

                        <button type="button"
                                class="btn btn-primary btn-sm !py-2 !px-4 flex items-center gap-1.5 shrink-0"
                                id="sendMessageButton">
                            <span>Send</span>
                            <i class="ti ti-send text-sm"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        {{-- Backdrop Overlay for Chat Details --}}
        <div id="infoDrawerBackdrop" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40" onclick="toggleChatDetails()"></div>

        {{-- ── 3. CONVERSATION DETAILS DRAWER ──────────────────────── --}}
        <div class="hidden fixed inset-y-0 right-0 z-50 w-80 bg-white border-l border-slate-200 shadow-2xl p-5 overflow-y-auto" id="infoDrawer">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-900 text-base m-0">Chat Details</h4>
                <button type="button" class="text-slate-400 hover:text-slate-600" onclick="toggleChatDetails()">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>

            {{-- Stat summary --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <div class="text-xl font-bold text-slate-900">{{ $currentConversation->messages->count() }}</div>
                    <div class="text-xs font-semibold text-slate-500">Messages</div>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <div class="text-xl font-bold text-slate-900">{{ $currentConversation->messageAttachments()->count() }}</div>
                    <div class="text-xs font-semibold text-slate-500">Files</div>
                </div>
            </div>

            {{-- Participants List --}}
            <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Participants</h5>
            <div class="space-y-3 mb-6">
                @foreach($currentConversation->users as $user)
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div class="tc-chat-avatar-circle !w-8 !h-8 !text-xs">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-900">{{ $user->name }}</div>
                                <div class="text-[10.5px] text-slate-400 capitalize">{{ $user->roles->first()?->name ?? 'User' }}</div>
                            </div>
                        </div>
                        @if(auth()->user()->isInternalAdmin() && $user->id !== auth()->id())
                            <button type="button"
                                    class="text-slate-400 hover:text-red-500 text-sm"
                                    data-userid="{{ $user->id }}"
                                    id="removeUserBtn"
                                    title="Remove Participant">
                                <i class="ti ti-user-minus"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Add Member Dropdown --}}
            @if(auth()->user()->isInternalAdmin())
                <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Add Participant</h5>
                <select class="form-control text-xs" id="addMemberSelect"></select>
            @endif
        </div>

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />
    <style>
        .msg-create-panel { padding: 0; background: #fff; border-bottom: 1px solid #e9edf3; }
        .msg-create-panel-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-bottom: 1px solid #f1f5f9; }
        .msg-create-panel-header h6 { margin: 0; color: #1e293b; font-size: 13px; font-weight: 700; }
        .msg-create-close { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: 8px; color: #94a3b8; background: transparent; cursor: pointer; }
        .msg-create-close:hover { color: #475569; background: #f1f5f9; }
        .msg-create-form { padding: 16px; }
        .msg-form-group { margin-bottom: 16px; }
        .msg-form-label { display: block; margin-bottom: 6px; color: #374151; font-size: 11px; font-weight: 700; letter-spacing: .02em; text-transform: uppercase; }
        .msg-form-input { width: 100%; height: 40px; padding: 0 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; outline: none; color: #1e293b; background: #f8fafc; font-size: 13px; transition: all .2s; }
        .msg-form-input:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .msg-create-form .choices.is-focused .choices__inner,
        .msg-create-form .choices.is-open .choices__inner { border-color: #e2e8f0 !important; background: #f8fafc !important; box-shadow: none !important; outline: none !important; }
        .msg-create-form .choices__input,
        .msg-create-form .choices__input:focus,
        .msg-create-form .choices__input:focus-visible { border: 0 !important; outline: none !important; box-shadow: none !important; background: transparent !important; }
        .msg-submit-btn { width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 20px; padding: 10px; border: 0; border-radius: 10px; color: #fff; background: linear-gradient(135deg,#4f46e5,#6366f1); box-shadow: 0 2px 8px rgba(79,70,229,.2); font-size: 13px; font-weight: 600; cursor: pointer; }
        .msg-submit-btn:hover { background: linear-gradient(135deg,#4338ca,#4f46e5); box-shadow: 0 4px 14px rgba(79,70,229,.3); }
    </style>
@endsection

@section('post-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="{{ asset('js/plugins/choices.min.js') }}"></script>

    <script>
        function toggleChatDetails() {
            $('#infoDrawer').toggleClass('hidden');
            $('#infoDrawerBackdrop').toggleClass('hidden');
        }

        // Conversation List Filtering
        function filterConversations() {
            const query = $('#searchConversations').val().toLowerCase();
            $('.tc-chat-conv-item').each(function() {
                const name = $(this).data('name');
                if (name.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Auto-scroll to bottom of chat
        function scrollToChatBottom() {
            const stream = document.querySelector('.tc-chat-stream-area');
            if (stream) {
                stream.scrollTop = stream.scrollHeight;
            }
        }

        $(document).ready(function() {
            scrollToChatBottom();

            // File Attachment selection
            $('#attachmentIcon').on('click', function(e) {
                e.preventDefault();
                $('#messageAttachment').click();
            });

            $('#messageAttachment').on('change', function() {
                const file = this.files[0];
                if (file) {
                    $('#attachmentName').text(file.name).removeClass('hidden');
                } else {
                    $('#attachmentName').addClass('hidden');
                }
            });

            // Send message on Enter
            $('#newMessageText').on('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            $('#sendMessageButton').on('click', sendMessage);

            function sendMessage() {
                const content = $('#newMessageText').val().trim();
                const fileInput = document.getElementById('messageAttachment');
                const file = fileInput.files[0];

                if (content === "" && !file) return;

                const formData = new FormData();
                formData.append('content', content);
                formData.append('conversation_id', '{{ $currentConversation->id }}');

                if (file) {
                    formData.append('attachments', file);
                }

                fetch('/api/conversations/{{ $currentConversation->id }}/messages', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    $('#newMessageText').val('');
                    fileInput.value = '';
                    $('#attachmentName').addClass('hidden');

                    // Append outgoing message bubble dynamically
                    const newMsgHtml = `
                        <div class="tc-msg-row outgoing" data-messageid="${data.id || ''}">
                            <div>
                                <div class="tc-msg-bubble-out">
                                    <p class="m-0 leading-relaxed">${content}</p>
                                </div>
                                <div class="flex items-center justify-end gap-1.5 mt-1 text-[11px] text-slate-400">
                                    <span>Just now</span>
                                    <i class="ti ti-check text-indigo-600 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    `;
                    $('.tc-chat-stream-area').append(newMsgHtml);
                    scrollToChatBottom();
                })
                .catch(err => console.error('Error sending message:', err));
            }

            // Echo realtime listener (if configured)
            if (window.Echo) {
                window.Echo.private(`conversations.{{ $currentConversation->id }}`)
                    .listen('MessageSent', (message) => {
                        // Append incoming message dynamically
                    });
            }

            // Choices setup for adding members
            if (document.querySelector('#Addparticipants')) {
                new Choices('#Addparticipants', {
                    placeholder: true,
                    placeholderValue: 'Select Users',
                    removeItemButton: true,
                    shouldSort: false
                }).setChoices(function() {
                    return fetch('{{ route("api.users.exclude") }}')
                        .then(res => res.json())
                        .then(data => data.map(u => ({ value: u.id, label: u.name })));
                });
            }

            if (document.querySelector('#addMemberSelect')) {
                const addChoices = new Choices('#addMemberSelect', {
                    placeholder: true,
                    placeholderValue: 'Select user to add',
                    shouldSort: false
                });
                addChoices.setChoices(function() {
                    return fetch('{{ route("api.users.exclude") }}')
                        .then(res => res.json())
                        .then(data => data.map(u => ({ value: u.id, label: u.name })));
                });

                $('#addMemberSelect').on('change', function() {
                    const userId = $(this).val();
                    if (!userId) return;

                    fetch(`/conversations/{{ $currentConversation->id }}/add-user`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        location.reload();
                    });
                });
            }
        });
    </script>
@endsection
