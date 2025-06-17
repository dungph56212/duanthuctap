class ChatBot {
    constructor() {
        this.sessionId = localStorage.getItem('chatbot_session_id') || null;
        this.isOpen = false;
        this.isTyping = false;
        this.messageHistory = [];
        
        this.init();
    }
    
    init() {
        this.createChatbotHTML();
        this.bindEvents();
        this.loadChatHistory();
    }
    
    createChatbotHTML() {
        const chatbotHTML = `
            <div class="chatbot-container">
                <button class="chatbot-toggle" id="chatbot-toggle">
                    <i class="fas fa-comments"></i>
                </button>
                
                <div class="chatbot-window" id="chatbot-window">
                    <div class="chatbot-header">
                        <div>
                            <h3>📚 BookBot</h3>
                            <small>Trợ lý ảo cửa hàng sách</small>
                        </div>
                        <button class="chatbot-close" id="chatbot-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="chatbot-messages" id="chatbot-messages">
                        <div class="welcome-message">
                            <div>🤖 Xin chào! Tôi là BookBot</div>
                            <div>Tôi có thể giúp bạn tìm sách, tư vấn và hỗ trợ mua hàng.</div>
                            <div class="quick-actions">
                                <span class="quick-action" data-message="Sách bán chạy">📈 Sách bán chạy</span>
                                <span class="quick-action" data-message="Thể loại sách">📂 Thể loại</span>
                                <span class="quick-action" data-message="Cách đặt hàng">🛒 Đặt hàng</span>
                                <span class="quick-action" data-message="Liên hệ">📞 Liên hệ</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chatbot-input">
                        <div class="chatbot-input-group">
                            <input type="text" id="chatbot-input" placeholder="Nhập câu hỏi của bạn..." maxlength="1000">
                            <button class="chatbot-send" id="chatbot-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }
    
    bindEvents() {
        const toggle = document.getElementById('chatbot-toggle');
        const close = document.getElementById('chatbot-close');
        const window = document.getElementById('chatbot-window');
        const input = document.getElementById('chatbot-input');
        const send = document.getElementById('chatbot-send');
        const quickActions = document.querySelectorAll('.quick-action');
        
        toggle.addEventListener('click', () => this.toggleChat());
        close.addEventListener('click', () => this.closeChat());
        
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        
        send.addEventListener('click', () => this.sendMessage());
        
        quickActions.forEach(action => {
            action.addEventListener('click', () => {
                const message = action.dataset.message;
                input.value = message;
                this.sendMessage();
            });
        });
        
        // Auto-close on outside click
        document.addEventListener('click', (e) => {
            if (this.isOpen && !window.contains(e.target) && !toggle.contains(e.target)) {
                this.closeChat();
            }
        });
    }
    
    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }
    
    openChat() {
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        
        window.classList.add('active');
        toggle.innerHTML = '<i class="fas fa-times"></i>';
        this.isOpen = true;
        
        // Focus input
        setTimeout(() => {
            document.getElementById('chatbot-input').focus();
        }, 300);
    }
    
    closeChat() {
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        
        window.classList.remove('active');
        toggle.innerHTML = '<i class="fas fa-comments"></i>';
        this.isOpen = false;
    }
    
    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (!message || this.isTyping) return;
        
        // Clear input
        input.value = '';
        
        // Add user message to chat
        this.addMessage(message, 'user');
        
        // Show typing indicator
        this.showTyping();
        
        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Update session ID
                this.sessionId = data.session_id;
                localStorage.setItem('chatbot_session_id', this.sessionId);
                
                // Hide typing and show response
                this.hideTyping();
                this.addMessage(data.response, 'bot', data.timestamp);
            } else {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            console.error('Chat error:', error);
            this.hideTyping();
            this.addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
        }
    }
    
    addMessage(message, sender, timestamp = null) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const welcomeMessage = messagesContainer.querySelector('.welcome-message');
        
        // Hide welcome message after first interaction
        if (welcomeMessage && this.messageHistory.length === 0) {
            welcomeMessage.style.display = 'none';
        }
        
        const messageElement = document.createElement('div');
        messageElement.className = `chatbot-message ${sender}`;
        
        const currentTime = timestamp || new Date().toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        const formattedMessage = this.formatMessage(message);
        
        messageElement.innerHTML = `
            <div class="message-bubble ${sender}">
                ${formattedMessage}
                <div class="message-time">${currentTime}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageElement);
        
        // Store in history
        this.messageHistory.push({ message, sender, timestamp: currentTime });
        
        // Scroll to bottom
        this.scrollToBottom();
        
        // Save to localStorage
        this.saveChatHistory();
    }
    
    formatMessage(message) {
        // Convert markdown-like formatting
        return message
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // **bold**
            .replace(/\*(.*?)\*/g, '<em>$1</em>') // *italic*
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>') // [text](url)
            .replace(/📚|📖|📂|💰|🔥|⭐|🔗|📊|✍️|🎁|💳|🚚|📞|📧|🕒|📍|😊|✨|🔍|📋|1️⃣|2️⃣|3️⃣|4️⃣|5️⃣|6️⃣|7️⃣|📱|🤖|📈|🛒|😔|💼|🧒|🎓/g, '<span class="emoji">$&</span>'); // Emojis
    }
    
    showTyping() {
        if (this.isTyping) return;
        
        this.isTyping = true;
        const messagesContainer = document.getElementById('chatbot-messages');
        const sendButton = document.getElementById('chatbot-send');
        
        const typingElement = document.createElement('div');
        typingElement.className = 'chatbot-message bot';
        typingElement.id = 'typing-indicator';
        typingElement.innerHTML = `
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        
        messagesContainer.appendChild(typingElement);
        sendButton.disabled = true;
        
        this.scrollToBottom();
    }
    
    hideTyping() {
        const typingIndicator = document.getElementById('typing-indicator');
        const sendButton = document.getElementById('chatbot-send');
        
        if (typingIndicator) {
            typingIndicator.remove();
        }
        
        sendButton.disabled = false;
        this.isTyping = false;
    }
    
    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    async loadChatHistory() {
        if (!this.sessionId) return;
        
        try {
            const response = await fetch(`/chat/history?session_id=${this.sessionId}`);
            const data = await response.json();
            
            if (data.messages && data.messages.length > 0) {
                const messagesContainer = document.getElementById('chatbot-messages');
                const welcomeMessage = messagesContainer.querySelector('.welcome-message');
                if (welcomeMessage) {
                    welcomeMessage.style.display = 'none';
                }
                
                data.messages.forEach(msg => {
                    this.addMessageToHistory(msg.message, msg.sender, msg.timestamp);
                });
            }
        } catch (error) {
            console.error('Error loading chat history:', error);
        }
    }
    
    addMessageToHistory(message, sender, timestamp) {
        const messagesContainer = document.getElementById('chatbot-messages');
        
        const messageElement = document.createElement('div');
        messageElement.className = `chatbot-message ${sender}`;
        
        const formattedMessage = this.formatMessage(message);
        
        messageElement.innerHTML = `
            <div class="message-bubble ${sender}">
                ${formattedMessage}
                <div class="message-time">${timestamp}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageElement);
        this.messageHistory.push({ message, sender, timestamp });
    }
    
    saveChatHistory() {
        localStorage.setItem('chatbot_history', JSON.stringify(this.messageHistory));
    }
    
    clearHistory() {
        this.messageHistory = [];
        localStorage.removeItem('chatbot_history');
        localStorage.removeItem('chatbot_session_id');
        this.sessionId = null;
        
        // Clear messages and show welcome
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.innerHTML = `
            <div class="welcome-message">
                <div>🤖 Xin chào! Tôi là BookBot</div>
                <div>Tôi có thể giúp bạn tìm sách, tư vấn và hỗ trợ mua hàng.</div>
                <div class="quick-actions">
                    <span class="quick-action" data-message="Sách bán chạy">📈 Sách bán chạy</span>
                    <span class="quick-action" data-message="Thể loại sách">📂 Thể loại</span>
                    <span class="quick-action" data-message="Cách đặt hàng">🛒 Đặt hàng</span>
                    <span class="quick-action" data-message="Liên hệ">📞 Liên hệ</span>
                </div>
            </div>
        `;
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a client page (not admin)
    if (!window.location.pathname.includes('/admin/')) {
        console.log('Initializing ChatBot...');
        window.chatBot = new ChatBot();
        console.log('ChatBot initialized successfully!');
    }
});
