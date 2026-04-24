@php
    $layout = auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'laveur' ? 'layouts.laveur' : 'layouts.client');
@endphp

@extends($layout)

@section('content')
<div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
    <h3 class="text-cyan-custom text-xl font-bold mb-8">Messagerie</h3>
    
    <div class="space-y-3">
        @forelse($conversations as $conversation)
            <a href="/chat/{{ $conversation->id }}" class="conversation-item {{ $conversation->unread_count > 0 ? 'unread' : '' }}">
                <div class="conversation-avatar">
                    @if($conversation->other_user->photo_profile)
                        <img src="{{ asset('uploads/profiles/' . $conversation->other_user->photo_profile) }}" alt="Avatar">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($conversation->other_user->name, 0, 1)) }}
                        </div>
                    @endif
                    @if($conversation->unread_count > 0)
                        <span class="unread-badge">{{ $conversation->unread_count }}</span>
                    @endif
                </div>
                
                <div class="conversation-info">
                    <div class="conversation-header-row">
                        <h3>{{ $conversation->other_user->name }}</h3>
                        <span class="conversation-time">
                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    
                    <div class="conversation-preview">
                        <span class="role-badge">
                            {{ ucfirst($conversation->other_user->role) }}
                        </span>
                        @if($conversation->lastMessage)
                            <p>{{ Str::limit($conversation->lastMessage->message, 50) }}</p>
                        @else
                            <p class="no-message">Aucun message</p>
                        @endif
                    </div>
                    
                    @if($conversation->commande)
                        <div class="conversation-commande">
                            <i class="fas fa-file-invoice"></i> Commande #{{ $conversation->commande->id }}
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="no-conversations">
                <i class="fas fa-inbox"></i>
                <p>Aucune conversation</p>
                <small>Vos conversations apparaîtront ici</small>
            </div>
        @endforelse
    </div>
</div>

<style>
.conversation-item {
    display: flex;
    gap: 15px;
    padding: 16px;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
    background: #404040;
    border: 2px solid transparent;
}

.conversation-item:hover {
    background: #4a4a4a;
    border-color: #00C2FF;
    transform: translateX(3px);
}

.conversation-item.unread {
    background: rgba(0, 194, 255, 0.1);
    border-color: #00C2FF;
}

.conversation-avatar {
    position: relative;
    flex-shrink: 0;
}

.conversation-avatar img,
.avatar-placeholder {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #00C2FF;
}

.avatar-placeholder {
    background: linear-gradient(135deg, #00C2FF, #0099cc);
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: bold;
}

.unread-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #00C2FF;
    color: #000000;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(0, 194, 255, 0.5);
}

.conversation-info {
    flex: 1;
    min-width: 0;
}

.conversation-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.conversation-info h3 {
    margin: 0;
    font-size: 17px;
    color: #ffffff;
    font-weight: 600;
}

.conversation-time {
    font-size: 12px;
    color: #999999;
}

.conversation-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
}

.conversation-preview p {
    margin: 0;
    color: #cccccc;
    font-size: 14px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.conversation-preview .no-message {
    font-style: italic;
    color: #999999;
}

.role-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    background: #00C2FF;
    color: #000000;
}

.conversation-commande {
    font-size: 12px;
    color: #00C2FF;
    margin-top: 4px;
    font-weight: 500;
}

.no-conversations {
    text-align: center;
    padding: 60px 20px;
    color: #999999;
}

.no-conversations i {
    font-size: 64px;
    margin-bottom: 20px;
    color: #00C2FF;
    opacity: 0.5;
}

.no-conversations p {
    font-size: 18px;
    margin: 10px 0;
    color: #cccccc;
    font-weight: 600;
}

.no-conversations small {
    color: #999999;
}

/* Responsive */
@media (max-width: 768px) {
    .conversation-item {
        padding: 14px;
    }
    
    .conversation-avatar img,
    .avatar-placeholder {
        width: 48px;
        height: 48px;
    }
    
    .conversation-info h3 {
        font-size: 16px;
    }
    
    .conversation-time {
        font-size: 11px;
    }
}
</style>
@endsection