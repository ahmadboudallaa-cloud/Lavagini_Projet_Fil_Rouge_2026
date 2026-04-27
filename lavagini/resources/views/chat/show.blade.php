@php
    $layout = auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'laveur' ? 'layouts.laveur' : 'layouts.client');
@endphp

@extends($layout)

@section('content')
<div class="bg-dark-card rounded-[30px] shadow-xl overflow-hidden" style="height: calc(100vh - 200px); display: flex; flex-direction: column;">
    <div class="chat-conversation-header">
        <a href="/chat" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <div class="chat-user-info">
            @if($otherUser->photo_profile)
                <img src="{{ asset('uploads/profiles/' . $otherUser->photo_profile) }}" alt="Avatar" class="chat-avatar">
            @else
                <div class="chat-avatar-placeholder">
                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                </div>
            @endif
            
            <div>
                <h2>{{ $otherUser->name }}</h2>
                <span class="role-badge">{{ ucfirst($otherUser->role) }}</span>
            </div>
        </div>
        
        @if($conversation->commande)
            <div class="chat-commande-info">
                <i class="fas fa-file-invoice"></i> Commande #{{ $conversation->commande->id }}
            </div>
        @endif
    </div>

    <div class="chat-messages" id="chatMessages">
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'message-sent' : 'message-received' }}">
                <div class="message-content">
                    <p>{{ $message->message }}</p>
                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <form class="chat-input-form" id="chatForm" action="{{ route('chat.send', $conversation->id) }}" method="POST">
        @csrf
        <textarea 
            name="message" 
            id="messageInput" 
            placeholder="Écrivez votre message..." 
            rows="1"
            required
        ></textarea>
        <button type="submit" class="send-btn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>

<style>
.chat-conversation-header {
    background: #404040;
    padding: 20px 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 2px solid #00C2FF;
    flex-shrink: 0;
}

.back-btn {
    color: #00C2FF;
    font-size: 22px;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0, 194, 255, 0.1);
}

.back-btn:hover {
    transform: translateX(-5px);
    background: rgba(0, 194, 255, 0.2);
}

.chat-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.chat-avatar,
.chat-avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #00C2FF;
}

.chat-avatar-placeholder {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
}

.chat-user-info h2 {
    margin: 0;
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
}

.role-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    margin-top: 4px;
    background: #00C2FF;
    color: #000000;
}

.chat-commande-info {
    color: #00C2FF;
    font-size: 13px;
    background: rgba(0, 194, 255, 0.15);
    padding: 6px 12px;
    border-radius: 16px;
    font-weight: 600;
    border: 1px solid rgba(0, 194, 255, 0.3);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 30px;
    background: #1a1a1a;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message {
    display: flex;
    animation: messageSlide 0.3s ease;
}

@keyframes messageSlide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-sent {
    justify-content: flex-end;
}

.message-received {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 16px;
    position: relative;
}

.message-sent .message-content {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 194, 255, 0.3);
    font-weight: 500;
}

.message-received .message-content {
    background: #404040;
    color: #ffffff;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.message-content p {
    margin: 0 0 4px 0;
    word-wrap: break-word;
    line-height: 1.4;
    font-size: 14px;
}

.message-time {
    font-size: 10px;
    opacity: 0.7;
    display: block;
    text-align: right;
}

.chat-input-form {
    padding: 20px 30px;
    background: #404040;
    border-top: 2px solid #00C2FF;
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-shrink: 0;
}

#messageInput {
    flex: 1;
    border: 2px solid #555;
    border-radius: 22px;
    padding: 10px 18px;
    font-size: 14px;
    resize: none;
    max-height: 100px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: #333333;
    color: #ffffff;
}

#messageInput:focus {
    outline: none;
    border-color: #00C2FF;
    box-shadow: 0 0 0 3px rgba(0, 194, 255, 0.1);
}

#messageInput::placeholder {
    color: #999999;
}

.send-btn {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    border: none;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    font-weight: bold;
}

.send-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 16px rgba(0, 194, 255, 0.4);
}

.send-btn:active {
    transform: scale(0.95);
}

/* Scrollbar personnalisée */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #000000;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #00C2FF;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #0099cc;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-conversation-header {
        padding: 15px 20px;
    }
    
    .chat-user-info h2 {
        font-size: 16px;
    }
    
    .chat-avatar,
    .chat-avatar-placeholder {
        width: 42px;
        height: 42px;
    }
    
    .chat-messages {
        padding: 15px 20px;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .chat-input-form {
        padding: 15px 20px;
    }
    
    .send-btn {
        width: 42px;
        height: 42px;
    }
}
</style>

<style>
.chat-conversation-header {
    background: #404040;
    padding: 20px 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 2px solid #00C2FF;
    flex-shrink: 0;
}

.back-btn {
    color: #00C2FF;
    font-size: 22px;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0, 194, 255, 0.1);
}

.back-btn:hover {
    transform: translateX(-5px);
    background: rgba(0, 194, 255, 0.2);
}

.chat-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.chat-avatar,
.chat-avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #00C2FF;
}

.chat-avatar-placeholder {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
}

.chat-user-info h2 {
    margin: 0;
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
}

.role-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    margin-top: 4px;
    background: #00C2FF;
    color: #000000;
}

.chat-commande-info {
    color: #00C2FF;
    font-size: 13px;
    background: rgba(0, 194, 255, 0.15);
    padding: 6px 12px;
    border-radius: 16px;
    font-weight: 600;
    border: 1px solid rgba(0, 194, 255, 0.3);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 30px;
    background: #1a1a1a;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message {
    display: flex;
    animation: messageSlide 0.3s ease;
}

@keyframes messageSlide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-sent {
    justify-content: flex-end;
}

.message-received {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 16px;
    position: relative;
}

.message-sent .message-content {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 194, 255, 0.3);
    font-weight: 500;
}

.message-received .message-content {
    background: #404040;
    color: #ffffff;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.message-content p {
    margin: 0 0 4px 0;
    word-wrap: break-word;
    line-height: 1.4;
    font-size: 14px;
}

.message-time {
    font-size: 10px;
    opacity: 0.7;
    display: block;
    text-align: right;
}

.chat-input-form {
    padding: 20px 30px;
    background: #404040;
    border-top: 2px solid #00C2FF;
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-shrink: 0;
}

#messageInput {
    flex: 1;
    border: 2px solid #555;
    border-radius: 22px;
    padding: 10px 18px;
    font-size: 14px;
    resize: none;
    max-height: 100px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: #333333;
    color: #ffffff;
}

#messageInput:focus {
    outline: none;
    border-color: #00C2FF;
    box-shadow: 0 0 0 3px rgba(0, 194, 255, 0.1);
}

#messageInput::placeholder {
    color: #999999;
}

.send-btn {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    border: none;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    font-weight: bold;
}

.send-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 16px rgba(0, 194, 255, 0.4);
}

.send-btn:active {
    transform: scale(0.95);
}

/* Scrollbar personnalisée */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #000000;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #00C2FF;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #0099cc;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-conversation-header {
        padding: 15px 20px;
    }
    
    .chat-user-info h2 {
        font-size: 16px;
    }
    
    .chat-avatar,
    .chat-avatar-placeholder {
        width: 42px;
        height: 42px;
    }
    
    .chat-messages {
        padding: 15px 20px;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .chat-input-form {
        padding: 15px 20px;
    }
    
    .send-btn {
        width: 42px;
        height: 42px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const conversationId = {{ $conversation->id }};
    let lastMessageId = {{ optional($messages->last())->id ?? 0 }};
    
    // Scroll vers le bas au chargement
    scrollToBottom();
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Envoyer le message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        fetch(`/chat/${conversationId}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessage(data.message, true);
                messageInput.value = '';
                messageInput.style.height = 'auto';
                lastMessageId = data.message.id;
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
    
    // Vérifier les nouveaux messages toutes les 3 secondes
    setInterval(checkNewMessages, 3000);
    
    function checkNewMessages() {
        fetch(`/chat/${conversationId}/messages?last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        addMessage(message, false);
                        lastMessageId = message.id;
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
    
    function addMessage(message, isSent) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isSent ? 'message-sent' : 'message-received'}`;
        
        const time = new Date(message.created_at).toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        messageDiv.innerHTML = `
            <div class="message-content">
                <p>${escapeHtml(message.message)}</p>
                <span class="message-time">${time}</span>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Envoyer avec Entrée, nouvelle ligne avec Shift+Entrée
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
@endsection
