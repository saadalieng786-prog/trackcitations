@extends('layout.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />
    <style>
        /* ── Messaging Layout ── */
        .msg-layout {
            display: flex;
            height: calc(100vh - 90px);
            gap: 0;
            background: #f8fafc;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 24px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        /* ── Sidebar ── */
        .msg-sidebar {
            width: 300px;
            min-width: 300px;
            background: #fff;
            border-right: 1px solid #e9edf3;
            display: flex;
            flex-direction: column;
        }

        .msg-sidebar-header {
            padding: 20px 20px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .msg-sidebar-header h5 {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .msg-sidebar-header p {
            font-size: 12px;
            color: #94a3b8;
            margin: 2px 0 0;
        }

        .msg-conversations {
            flex: 1;
            overflow-y: auto;
        }

        .msg-conv-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f8fafc;
            text-decoration: none !important;
            transition: background 0.15s ease;
            position: relative;
        }

        .msg-conv-item:hover {
            background: #f8fafc;
        }

        .msg-conv-item.active {
            background: #eff6ff;
            border-left: 3px solid #4f46e5;
        }

        .msg-conv-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .msg-conv-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .msg-conv-info {
            flex: 1;
            min-width: 0;
        }

        .msg-conv-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .msg-conv-time {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── Main Chat Area ── */
        .msg-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
            min-width: 0;
        }

        .msg-main-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            min-height: 64px;
        }

        .msg-main-header .left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── New Conversation button ── */
        .btn-new-conv {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff !important;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        .btn-new-conv:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        /* ── Empty State ── */
        .msg-empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 40px;
        }

        .msg-empty-icon {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e0e7ff;
        }

        .msg-empty-icon i {
            font-size: 38px;
            color: #6366f1;
        }

        .msg-empty-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .msg-empty-sub {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
            text-align: center;
            max-width: 280px;
            line-height: 1.6;
        }

        /* ── Mobile toggle ── */
        .msg-mobile-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #e9edf3;
            background: #f8fafc;
            cursor: pointer;
            color: #64748b;
            text-decoration: none;
        }

        @media (max-width: 1200px) {
            .msg-mobile-toggle { display: flex; }
        }

        /* ── New Conversation Panel ── */
        .msg-new-conv-panel {
            width: 280px;
            min-width: 280px;
            background: #fff;
            border-left: 1px solid #e9edf3;
            display: flex;
            flex-direction: column;
        }

        .msg-new-conv-header {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .msg-new-conv-header h5 {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .msg-new-conv-body {
            padding: 20px;
            flex: 1;
        }

        .msg-form-label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 6px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .msg-form-input {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 12px;
            font-size: 13px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }

        .msg-form-input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        /* Keep the Add Members dropdown neutral while its search input is active. */
        .msg-new-conv-body .choices.is-focused .choices__inner,
        .msg-new-conv-body .choices.is-open .choices__inner {
            border-color: #e2e8f0 !important;
            background: #f8fafc !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .msg-new-conv-body .choices__input,
        .msg-new-conv-body .choices__input:focus,
        .msg-new-conv-body .choices__input:focus-visible {
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .msg-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .msg-submit-btn:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .msg-form-group {
            margin-bottom: 16px;
        }
    </style>
@endsection

@section('content')
<div class="col-span-12">
    <div class="msg-layout">

        {{-- ── LEFT SIDEBAR: Conversation List ── --}}
        <div class="msg-sidebar" id="msgSidebar">
            <div class="msg-sidebar-header">
                <h5>Messages</h5>
                <p>{{ $conversations->count() }} conversation{{ $conversations->count() !== 1 ? 's' : '' }}</p>
            </div>

            <div class="msg-conversations">
                @forelse($conversations as $conversation)
                    @php
                        $initials = collect(explode(' ', $conversation->name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                    @endphp
                    <a href="{{ route('messaging.show', $conversation->id) }}" class="msg-conv-item">
                        <div class="msg-conv-avatar">
                            {{ $initials ?: '?' }}
                        </div>
                        <div class="msg-conv-info">
                            <div class="msg-conv-name">{{ $conversation->name }}</div>
                            <div class="msg-conv-time">{{ \Carbon\Carbon::parse($conversation->updated_at)->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                    <div style="padding: 40px 20px; text-align: center;">
                        <i class="ti ti-messages" style="font-size:32px; color:#cbd5e1; display:block; margin-bottom:10px;"></i>
                        <p style="font-size:13px; color:#94a3b8; margin:0;">No conversations yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── CENTER: Chat Area (Empty State) ── --}}
        <div class="msg-main">
            <div class="msg-main-header">
                <div class="left">
                    <a href="#" class="msg-mobile-toggle" data-pc-toggle="offcanvas" data-pc-target="#offcanvas_User_list">
                        <i class="ti ti-menu-2 text-base"></i>
                    </a>
                </div>
                <div>
                    @if(auth()->user()->isInternalAdmin())
                        <a href="#" class="btn-new-conv" data-pc-toggle="collapse" data-pc-target="#chat-new_chat">
                            <i class="ti ti-plus text-sm"></i>
                            New Conversation
                        </a>
                    @endif
                </div>
            </div>

            {{-- Empty state --}}
            <div class="msg-empty-state">
                <div class="msg-empty-icon">
                    <i class="ti ti-messages"></i>
                </div>
                <h6 class="msg-empty-title">No conversation selected</h6>
                <p class="msg-empty-sub">Choose a conversation from the sidebar, or start a new one to begin messaging.</p>
                @if(auth()->user()->isInternalAdmin())
                    <a href="#" class="btn-new-conv" style="margin-top:4px;" data-pc-toggle="collapse" data-pc-target="#chat-new_chat">
                        <i class="ti ti-plus text-sm"></i>
                        Start a Conversation
                    </a>
                @endif
            </div>
        </div>

        {{-- ── RIGHT PANEL: New Conversation (Admin Only) ── --}}
        @if(auth()->user()->isInternalAdmin())
        <div id="chat-new_chat" class="collapse-horizontal msg-new-conv-panel" style="display:none; width:0; overflow:hidden; transition: width 0.3s ease;">
            <div class="msg-new-conv-header">
                <h5>New Conversation</h5>
                <a href="#" style="color:#94a3b8; font-size:16px; line-height:1; text-decoration:none;"
                   data-pc-toggle="collapse" data-pc-target="#chat-new_chat">
                    <i class="ti ti-x"></i>
                </a>
            </div>
            <div class="msg-new-conv-body">
                <form id="createConversationForm" method="POST" action="{{ route('messaging.conversations.store') }}">
                    @csrf
                    <div class="msg-form-group">
                        <label class="msg-form-label" for="conversationName">Conversation Name <span class="text-red-500">*</span></label>
                        <input type="text" id="conversationName" name="name" class="msg-form-input"
                               placeholder="e.g. Ticket #1234 Discussion" required>
                    </div>
                    <div class="msg-form-group">
                        <label class="msg-form-label" for="Addparticipants">Add Members <span class="text-red-500">*</span></label>
                        <select class="form-control" name="user_id[]" id="Addparticipants" multiple>
                            @foreach(($users ?? []) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="msg-submit-btn">
                        <i class="ti ti-send text-sm"></i>
                        Create Conversation
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('post-scripts')
    <script src="{{ asset('js/plugins/choices.min.js') }}"></script>
    <script>
        // New conversation panel toggle
        document.querySelectorAll('[data-pc-target="#chat-new_chat"]').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                var panel = document.getElementById('chat-new_chat');
                if (!panel) return;
                if (panel.style.display === 'none' || panel.style.width === '0px' || panel.style.width === '') {
                    panel.style.display = 'flex';
                    panel.style.flexDirection = 'column';
                    panel.style.width = '280px';
                    panel.style.minWidth = '280px';
                } else {
                    panel.style.width = '0px';
                    panel.style.minWidth = '0px';
                    setTimeout(function() { panel.style.display = 'none'; }, 300);
                }
            });
        });

        // Choices.js for member select
        var participantsEl = document.querySelector('#Addparticipants');
        var participantsChoices = null;
        if (participantsEl && typeof Choices !== 'undefined') {
            participantsChoices = new Choices(participantsEl, {
                placeholder: true,
                placeholderValue: 'Search and select users...',
                removeItemButton: true,
                itemSelectText: '',
                shouldSort: false,
                searchEnabled: true,
            });
        }

        document.getElementById('createConversationForm')?.addEventListener('submit', function(e) {
            var selected = participantsChoices
                ? participantsChoices.getValue(true)
                : Array.from(participantsEl?.selectedOptions || []).map(function(o) { return o.value; });
            if (!selected || selected.length === 0) {
                e.preventDefault();
                alert('Please select at least one member.');
            }
        });
    </script>
@endsection
