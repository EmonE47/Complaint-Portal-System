<!-- Shared chat modal partial (Bootstrap + Tailwind) -->
<!-- NOTE: this partial includes CDN links for Tailwind Play and Bootstrap CSS so utilities are immediately available.
         For production, move these imports to the main layout and build Tailwind properly. -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
/* Chat Modal Styles */
.modal-header.bg-gradient-to-r {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%) !important;
    padding: 1.25rem 1.5rem;
}

.modal-header.bg-gradient-to-r .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.modal-header.bg-gradient-to-r .btn-close:hover {
    opacity: 1;
}

#chat-participant-avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: white;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.125rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

#chatModalLabel {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.125rem;
}

#chat-participant-status {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 400;
}

.modal-body.bg-slate-50 {
    background-color: #f8fafc !important;
    padding: 0;
}

.chat-messages {
    height: 24rem;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Message Styles */
.message {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 1.125rem;
    position: relative;
    word-wrap: break-word;
    animation: messageSlideIn 0.3s ease-out;
}

.message.sent {
    align-self: flex-end;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-bottom-right-radius: 0.5rem;
    margin-left: auto;
}

.message.received {
    align-self: flex-start;
    background: white;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.message-content {
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.message-content strong {
    display: block;
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    opacity: 0.9;
}

.message.sent .message-content strong {
    color: rgba(255, 255, 255, 0.9);
}

.message.received .message-content strong {
    color: #475569;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    text-align: right;
}

.message.received .message-time {
    text-align: left;
    color: #64748b;
}

.message.sent .message-time {
    color: rgba(255, 255, 255, 0.8);
}

/* Input Area Styles */
.mt-3 {
    padding: 1rem 1.5rem;
    background: white;
    border-top: 1px solid #e2e8f0;
}

.form-control.rounded-pill {
    border-radius: 50px !important;
    border: 2px solid #e2e8f0;
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-control.rounded-pill:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.btn-outline-secondary.rounded-circle {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 50% !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e2e8f0;
    background: white;
    transition: all 0.3s ease;
}

.btn-outline-secondary.rounded-circle:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: white;
}

.btn-primary.rounded-pill {
    border-radius: 50px !important;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary.rounded-pill:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Typing Indicator */
#chat-typing {
    font-size: 0.75rem;
    color: #64748b;
    font-style: italic;
    padding-left: 0.5rem;
}

/* Modal Responsive Adjustments */
@media (max-width: 768px) {
    .modal-dialog.modal-lg {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .chat-messages {
        height: 20rem;
        padding: 0.75rem;
    }
    
    .message {
        max-width: 85%;
    }
    
    .modal-header.bg-gradient-to-r {
        padding: 1rem 1.25rem;
    }
    
    #chat-participant-avatar {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }
}

/* Animations */
@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Empty State */
.chat-messages:empty::before {
    content: "No messages yet. Start the conversation!";
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #64748b;
    font-style: italic;
    text-align: center;
}

/* Loading State */
.chat-messages.loading::after {
    content: "Loading messages...";
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #64748b;
    font-style: italic;
}

/* Message Status Indicators */
.message.sent::after {
    content: "✓";
    position: absolute;
    bottom: 0.5rem;
    right: 1rem;
    font-size: 0.625rem;
    opacity: 0.7;
}

.message.sent.delivered::after {
    content: "✓✓";
}

.message.sent.read::after {
    content: "✓✓";
    color: #34d399;
}

/* Focus States for Accessibility */
.btn:focus,
.form-control:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .modal-body.bg-slate-50 {
        background-color: #1e293b !important;
    }
    
    .message.received {
        background: #334155;
        color: #f1f5f9;
        border-color: #475569;
    }
    
    .form-control.rounded-pill {
        background: #1e293b;
        border-color: #475569;
        color: #f1f5f9;
    }
    
    .form-control.rounded-pill:focus {
        background: #1e293b;
        color: #f1f5f9;
    }
    
    .btn-outline-secondary.rounded-circle {
        background: #1e293b;
        border-color: #475569;
        color: #94a3b8;
    }
}
</style>

<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-blue-600 to-purple-600 text-white border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white text-blue-600 flex items-center justify-center font-bold text-lg" id="chat-participant-avatar">U</div>
                    <div class="leading-tight">
                        <div id="chatModalLabel" class="text-sm font-semibold">Participant</div>
                        <div id="chat-participant-status" class="text-xs opacity-80">Online</div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-light ms-auto" data-bs-dismiss="modal" aria-label="Close">✕</button>
            </div>

            <div class="modal-body bg-slate-50">
                <div id="chat-messages" class="chat-messages overflow-y-auto p-4 h-96 flex flex-col gap-3">
                    <!-- messages will be injected here by JS -->
                </div>

                <div class="mt-3">
                    <div class="d-flex gap-2 items-center">
                        <input id="chat-message" type="text" maxlength="1000" autocomplete="off" placeholder="Write a message..."
                                     class="form-control rounded-pill shadow-sm flex-grow-1 px-4 py-2" />
                        <button id="chat-attach" class="btn btn-outline-secondary ms-2 rounded-circle" title="Attach">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button id="send-message" class="btn btn-primary ms-2 rounded-pill px-4">Send</button>
                    </div>
                    <div class="mt-2 text-xs text-gray-500" id="chat-typing" style="display:none">Typing…</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>