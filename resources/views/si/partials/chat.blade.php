<!-- Chat Modal Partial: resources/views/si/partials/chat.blade.php -->
<!-- This partial contains the chat modal used by the SI dashboard. Included from si/dashboard.blade.php -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">Chat with User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                    <!-- Messages will be loaded here -->
                </div>
                <div class="input-group">
                    <input type="text" id="chat-message" class="form-control" placeholder="Type your message..." maxlength="1000">
                    <button class="btn btn-primary" id="send-message">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
