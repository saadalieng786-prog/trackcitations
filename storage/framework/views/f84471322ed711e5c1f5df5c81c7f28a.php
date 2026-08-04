<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <div class="tc-chat-app-shell">

            
            <div class="tc-chat-pane-left">
                <div class="tc-chat-sidebar-header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 m-0">Messages</h2>
                        <?php if(auth()->user()->isInternalAdmin()): ?>
                            <button type="button"
                                    class="btn btn-primary btn-sm !py-1 !px-2.5 flex items-center gap-1 text-xs"
                                    id="openNewChatBtn">
                                <i class="ti ti-plus text-sm"></i> New
                            </button>
                        <?php endif; ?>
                    </div>

                    
                    <div class="tc-chat-sidebar-search">
                        <i class="ti ti-search"></i>
                        <input type="text" id="searchConversations" placeholder="Search conversations..." onkeyup="filterConversations()">
                    </div>
                </div>

                
                <?php if(auth()->user()->isInternalAdmin()): ?>
                    <div class="tc-is-hidden msg-create-panel" id="newChatCollapse">
                        <div class="msg-create-panel-header">
                            <h6>Create New Conversation</h6>
                            <button type="button" class="msg-create-close" id="closeNewChatBtn" aria-label="Close">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        <form method="POST" action="<?php echo e(route('messaging.conversations.store')); ?>" class="msg-create-form" id="createConversationForm">
                            <?php echo csrf_field(); ?>
                            <div class="msg-form-group">
                                <label class="msg-form-label" for="conversationName">Conversation Name <span class="text-red-500">*</span></label>
                                <input type="text" id="conversationName" name="name" class="msg-form-input" placeholder="e.g. Ticket #1234 Discussion" required>
                            </div>
                            <div class="msg-form-group">
                                <label class="msg-form-label" for="Addparticipants">Add Members <span class="text-red-500">*</span></label>
                                <select class="form-control" name="user_id[]" id="Addparticipants" multiple>
                                    <?php $__currentLoopData = ($users ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit" class="msg-submit-btn">
                                <i class="ti ti-send text-sm"></i>
                                Create Conversation
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                
                <div class="tc-chat-conversations-list scroll-block">
                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $isActive = $conversation->id === $currentConversation->id; ?>
                        <a href="<?php echo e(route('messaging.show', $conversation->id)); ?>"
                           class="tc-chat-conv-item <?php echo e($isActive ? 'active' : ''); ?>"
                           data-name="<?php echo e(strtolower($conversation->name)); ?>">
                            <div class="tc-chat-avatar-circle online">
                                <?php echo e(strtoupper(substr($conversation->name, 0, 2))); ?>

                            </div>
                            <div class="grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h6 class="font-bold text-slate-900 text-xs truncate m-0"><?php echo e($conversation->name); ?></h6>
                                    <span class="text-[10.5px] text-slate-400 font-medium shrink-0 ml-1">
                                        <?php echo e(\Carbon\Carbon::parse($conversation->updated_at)->diffForHumans(null, true, true)); ?>

                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 truncate m-0">
                                    <?php if($conversation->messages->last()): ?>
                                        <?php echo e($conversation->messages->last()->content ?: 'Attachment file'); ?>

                                    <?php else: ?>
                                        No messages yet
                                    <?php endif; ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="tc-chat-pane-right">

                
                <div class="tc-chat-main-header">
                    <div class="flex items-center gap-3">
                        <div class="tc-chat-avatar-circle">
                            <?php echo e(strtoupper(substr($currentConversation->name, 0, 2))); ?>

                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 m-0 leading-tight"><?php echo e($currentConversation->name); ?></h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active Now
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="text-[11px] text-slate-500"><?php echo e($currentConversation->users->count()); ?> Participants</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm !py-1.5 !px-3 flex items-center gap-1.5 text-xs"
                                onclick="toggleChatDetails()">
                            <i class="ti ti-info-circle text-sm"></i>
                            <span>Details</span>
                        </button>
                    </div>
                </div>

                
                <div class="tc-chat-stream-area scroll-block">
                    <?php $__currentLoopData = $currentConversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $sender = $message->sender; ?>
                        <?php if($sender && $sender->id === auth()->id()): ?>
                            
                            <div class="tc-msg-row outgoing" data-messageid="<?php echo e($message->id); ?>">
                                <div>
                                    <div class="tc-msg-bubble-out">
                                        <?php if($message->content !== ''): ?>
                                            <p class="m-0 leading-relaxed"><?php echo e($message->content); ?></p>
                                        <?php endif; ?>
                                        <?php $__currentLoopData = $message->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('messaging.attachments.download', $attachment->id)); ?>" target="_blank" rel="noopener" class="tc-msg-attachment-pill">
                                                <i class="ti ti-file-download text-base"></i>
                                                <span><?php echo e($attachment->file_name); ?></span>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="flex items-center justify-end gap-1.5 mt-1 text-[11px] text-slate-400">
                                        <span><?php echo e(\Carbon\Carbon::parse($message->created_at)->diffForHumans()); ?></span>
                                        <i class="ti ti-checks text-indigo-600 text-sm <?php echo e(!$message->isReadByAnyone() ? 'opacity-40' : ''); ?>"></i>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            
                            <div class="tc-msg-row incoming" data-messageid="<?php echo e($message->id); ?>">
                                <div class="tc-chat-avatar-circle shrink-0 !w-8 !h-8 !text-xs">
                                    <?php echo e(strtoupper(substr(optional($sender)->name ?? 'U', 0, 2))); ?>

                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 mb-1 ml-1">
                                        <?php echo e(optional($sender)->name ?? 'Unknown User'); ?>

                                    </div>
                                    <div class="tc-msg-bubble-in">
                                        <?php if($message->content !== ''): ?>
                                            <p class="m-0 leading-relaxed"><?php echo e($message->content); ?></p>
                                        <?php endif; ?>
                                        <?php $__currentLoopData = $message->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('messaging.attachments.download', $attachment->id)); ?>" target="_blank" rel="noopener" class="tc-msg-attachment-pill">
                                                <i class="ti ti-file-download text-base"></i>
                                                <span><?php echo e($attachment->file_name); ?></span>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1 ml-1">
                                        <?php echo e(\Carbon\Carbon::parse($message->created_at)->diffForHumans()); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="tc-chat-composer-wrap">
                    <div class="tc-chat-composer-box">
                        <button type="button"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                id="attachmentIcon"
                                title="Attach File">
                            <i class="ti ti-paperclip text-lg"></i>
                        </button>
                        <input type="file" id="messageAttachment" class="tc-is-hidden" />

                        <textarea id="newMessageText" placeholder="Type a message... (Press Enter to send)" rows="1"></textarea>

                        <span class="tc-is-hidden badge bg-indigo-100 text-indigo-700 font-semibold px-2 py-1 text-xs rounded" id="attachmentName"></span>

                        <button type="button"
                                class="btn btn-primary btn-sm !py-2 !px-4 flex items-center gap-1.5 shrink-0"
                                id="sendMessageButton">
                            <span id="sendMessageLabel">Send</span>
                            <i class="ti ti-send text-sm" id="sendMessageIcon"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        
        <div id="infoDrawerBackdrop" class="tc-is-hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40" onclick="toggleChatDetails()"></div>

        
        <div class="tc-is-hidden fixed inset-y-0 right-0 z-50 w-80 bg-white border-l border-slate-200 shadow-2xl p-5 overflow-y-auto" id="infoDrawer">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-900 text-base m-0">Chat Details</h4>
                <button type="button" class="text-slate-400 hover:text-slate-600" onclick="toggleChatDetails()">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>

            
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <div class="text-xl font-bold text-slate-900"><?php echo e($currentConversation->messages->count()); ?></div>
                    <div class="text-xs font-semibold text-slate-500">Messages</div>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <div class="text-xl font-bold text-slate-900"><?php echo e($currentConversation->messageAttachments()->count()); ?></div>
                    <div class="text-xs font-semibold text-slate-500">Files</div>
                </div>
            </div>

            
            <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Participants</h5>
            <div class="space-y-3 mb-6">
                <?php $__currentLoopData = $currentConversation->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div class="tc-chat-avatar-circle !w-8 !h-8 !text-xs">
                                <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-900"><?php echo e($user->name); ?></div>
                                <div class="text-[10.5px] text-slate-400 capitalize"><?php echo e($user->roles->first()?->name ?? 'User'); ?></div>
                            </div>
                        </div>
                        <?php if(auth()->user()->isInternalAdmin() && $user->id !== auth()->id()): ?>
                            <button type="button"
                                    class="text-slate-400 hover:text-red-500 text-sm remove-user-btn"
                                    data-userid="<?php echo e($user->id); ?>"
                                    title="Remove Participant">
                                <i class="ti ti-user-minus"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <?php if(auth()->user()->isInternalAdmin()): ?>
                <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Add Participant</h5>
                <select class="form-control text-xs" id="addMemberSelect">
                    <option value="">Select user to add</option>
                    <?php $__currentLoopData = ($users ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>

    <script>
        function toggleChatDetails() {
            document.getElementById('infoDrawer')?.classList.toggle('tc-is-hidden');
            document.getElementById('infoDrawerBackdrop')?.classList.toggle('tc-is-hidden');
        }

        function filterConversations() {
            const query = (document.getElementById('searchConversations')?.value || '').toLowerCase();
            document.querySelectorAll('.tc-chat-conv-item').forEach(function(item) {
                const name = (item.getAttribute('data-name') || '');
                item.style.display = name.includes(query) ? '' : 'none';
            });
        }

        function scrollToChatBottom() {
            const stream = document.querySelector('.tc-chat-stream-area');
            if (stream) {
                stream.scrollTop = stream.scrollHeight;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            scrollToChatBottom();

            const newChatPanel = document.getElementById('newChatCollapse');
            document.getElementById('openNewChatBtn')?.addEventListener('click', function() {
                newChatPanel?.classList.toggle('tc-is-hidden');
            });
            document.getElementById('closeNewChatBtn')?.addEventListener('click', function() {
                newChatPanel?.classList.add('tc-is-hidden');
            });

            const fileInput = document.getElementById('messageAttachment');
            const attachmentName = document.getElementById('attachmentName');
            document.getElementById('attachmentIcon')?.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput?.click();
            });
            fileInput?.addEventListener('change', function() {
                if (this.files[0]) {
                    attachmentName.textContent = this.files[0].name;
                    attachmentName.classList.remove('tc-is-hidden');
                } else {
                    attachmentName.classList.add('tc-is-hidden');
                }
            });

            const messageInput = document.getElementById('newMessageText');
            const sendBtn = document.getElementById('sendMessageButton');
            const sendLabel = document.getElementById('sendMessageLabel');
            const sendIcon = document.getElementById('sendMessageIcon');
            let sending = false;

            function setSendingState(isSending) {
                sending = isSending;
                if (sendBtn) sendBtn.disabled = isSending;
                if (messageInput) messageInput.disabled = isSending;
                if (sendLabel) sendLabel.textContent = isSending ? 'Sending...' : 'Send';
                if (sendIcon) {
                    sendIcon.className = isSending ? 'ti ti-loader-2 text-sm animate-spin' : 'ti ti-send text-sm';
                }
            }

            function sendMessage() {
                if (sending) return;
                const content = (messageInput?.value || '').trim();
                const file = fileInput?.files?.[0];

                // Block empty messages (no text and no file).
                if (!content && !file) {
                    messageInput?.focus();
                    return;
                }

                setSendingState(true);

                const formData = new FormData();
                formData.append('content', content);
                formData.append('conversation_id', '<?php echo e($currentConversation->id); ?>');
                if (file) formData.append('attachments', file);

                fetch('<?php echo e(route('messaging.messages.store', $currentConversation->id)); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(async function(res) {
                    const data = await res.json().catch(function() { return {}; });
                    if (!res.ok) {
                        const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to send message');
                        throw new Error(msg);
                    }
                    return data;
                })
                .then(function(data) {
                    if (messageInput) messageInput.value = '';
                    if (fileInput) fileInput.value = '';
                    attachmentName?.classList.add('tc-is-hidden');

                    const safeText = (content || (file ? file.name : '')).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    let attachmentHtml = '';
                    if (data.attachment && data.attachment.id) {
                        const fileName = (data.attachment.file_name || 'Attachment').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        attachmentHtml = `<a href="/messaging/attachments/${data.attachment.id}/download" target="_blank" rel="noopener" class="tc-msg-attachment-pill"><i class="ti ti-file-download text-base"></i><span>${fileName}</span></a>`;
                    }

                    const newMsgHtml = `
                        <div class="tc-msg-row outgoing" data-messageid="${data.id || (data.message && data.message.id) || ''}">
                            <div>
                                <div class="tc-msg-bubble-out">
                                    ${content ? `<p class="m-0 leading-relaxed">${safeText}</p>` : ''}
                                    ${attachmentHtml}
                                </div>
                                <div class="flex items-center justify-end gap-1.5 mt-1 text-[11px] text-slate-400">
                                    <span>Just now</span>
                                    <i class="ti ti-check text-indigo-600 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    `;
                    document.querySelector('.tc-chat-stream-area')?.insertAdjacentHTML('beforeend', newMsgHtml);
                    scrollToChatBottom();
                })
                .catch(function(err) {
                    console.error('Error sending message:', err);
                    alert(err.message || 'Failed to send message');
                })
                .finally(function() {
                    setSendingState(false);
                });
            }

            messageInput?.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            sendBtn?.addEventListener('click', sendMessage);

            let participantsChoices = null;
            if (document.querySelector('#Addparticipants') && typeof Choices !== 'undefined') {
                participantsChoices = new Choices('#Addparticipants', {
                    placeholder: true,
                    placeholderValue: 'Select Users',
                    removeItemButton: true,
                    shouldSort: false,
                    searchEnabled: true,
                });
            }

            document.getElementById('createConversationForm')?.addEventListener('submit', function(e) {
                const selected = participantsChoices
                    ? participantsChoices.getValue(true)
                    : Array.from(document.getElementById('Addparticipants')?.selectedOptions || []).map(o => o.value);
                if (!selected || selected.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one member.');
                }
            });

            if (document.querySelector('#addMemberSelect') && typeof Choices !== 'undefined') {
                const addChoices = new Choices('#addMemberSelect', {
                    placeholder: true,
                    placeholderValue: 'Select user to add',
                    shouldSort: false,
                    searchEnabled: true,
                });

                document.getElementById('addMemberSelect')?.addEventListener('change', function() {
                    const userId = addChoices.getValue(true);
                    if (!userId) return;

                    fetch('<?php echo e(route('conversation.addUser', $currentConversation->id)); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(async function(res) {
                        const data = await res.json().catch(function() { return {}; });
                        if (!res.ok) throw new Error(data.message || 'Failed to add user');
                        location.reload();
                    })
                    .catch(function(err) {
                        alert(err.message || 'Failed to add user');
                    });
                });
            }

            document.querySelectorAll('.remove-user-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const userId = btn.getAttribute('data-userid');
                    if (!userId || !confirm('Remove this participant?')) return;

                    fetch(`/conversations/<?php echo e($currentConversation->id); ?>/remove-user/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(async function(res) {
                        const data = await res.json().catch(function() { return {}; });
                        if (!res.ok) throw new Error(data.message || 'Failed to remove user');
                        location.reload();
                    })
                    .catch(function(err) {
                        alert(err.message || 'Failed to remove user');
                    });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\messaging\show.blade.php ENDPATH**/ ?>