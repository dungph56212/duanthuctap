# 🤖 CHATBOT AI HƯỚNG DẪN SỬ DỤNG

## 🚀 TÍNH NĂNG

### ✨ ChatBot AI có thể:
- **Tìm kiếm sách**: Theo tên, tác giả, thể loại
- **Tư vấn sản phẩm**: Sách bán chạy, giá tốt
- **Hướng dẫn mua hàng**: Từng bước đặt hàng
- **Thông tin liên hệ**: Hotline, email, địa chỉ
- **Lưu lịch sử chat**: Theo session

### 🎯 Ví dụ câu hỏi:
```
User: "Xin chào"
Bot: Chào mừng + menu hỗ trợ

User: "Tìm sách tiểu thuyết"
Bot: Danh sách sách tiểu thuyết + giá + link

User: "Sách nào bán chạy?"
Bot: Top 5 sách bán chạy nhất

User: "Cách đặt hàng?"
Bot: 7 bước hướng dẫn chi tiết

User: "Liên hệ"
Bot: Hotline, email, địa chỉ, giờ làm việc
```

## 🛠️ CÀI ĐẶT

### 1. Chạy setup database:
```bash
php setup_chatbot_simple.php
```

### 2. Kiểm tra files:
```
✅ app/Http/Controllers/Client/ChatBotController.php
✅ app/Models/ChatMessage.php
✅ public/css/chatbot.css
✅ public/js/chatbot.js
✅ routes/web.php (updated)
✅ resources/views/client/layouts/app.blade.php (updated)
```

### 3. Database tables:
```sql
chat_messages (id, session_id, message, sender, response, context, timestamps)
products.sold_count (new column)
```

## 🎨 GIAO DIỆN

### Vị trí:
- **Góc phải dưới** trang client
- **Nút tròn** gradient xanh-tím
- **Cửa sổ chat** 350x500px

### Tính năng UI:
- **Quick Actions**: Sách bán chạy, Thể loại, Đặt hàng, Liên hệ
- **Typing indicator**: Hiệu ứng đang trả lời
- **Message bubbles**: User (xanh), Bot (trắng)
- **Auto-scroll**: Tự động cuộn xuống
- **Responsive**: Mobile-friendly

## 🔗 API ENDPOINTS

### POST /chat
```json
Request:
{
    "message": "tìm sách văn học",
    "session_id": "uuid" // optional
}

Response:
{
    "session_id": "uuid",
    "response": "🔍 Kết quả tìm kiếm...",
    "timestamp": "14:30"
}
```

### GET /chat/history
```
GET /chat/history?session_id=uuid

Response:
{
    "messages": [
        {
            "message": "xin chào",
            "sender": "user",
            "timestamp": "14:29"
        }
    ]
}
```

## 💬 PATTERN MATCHING

ChatBot hiểu các từ khóa:

### Chào hỏi:
`chào`, `hello`, `hi`, `xin chào`

### Tìm kiếm:
`tìm`, `search`, `sách`, `book`

### Giá cả:
`giá`, `price`, `bao nhiêu`, `chi phí`

### Bán chạy:
`bán chạy`, `phổ biến`, `nổi tiếng`, `bestseller`

### Đặt hàng:
`đặt hàng`, `mua`, `order`, `thanh toán`

### Liên hệ:
`liên hệ`, `contact`, `hotline`, `phone`

## 🎯 QUICK ACTIONS

Các nút tắt khi mở chat:
- 📈 **Sách bán chạy**
- 📂 **Thể loại**
- 🛒 **Đặt hàng**
- 📞 **Liên hệ**

## 📱 RESPONSIVE

### Desktop:
- Chatbot: 350x500px
- Button: 60x60px

### Mobile:
- Chatbot: 320x450px
- Button: 55x55px

## 🔧 CUSTOMIZATION

### Thay đổi màu sắc:
```css
/* public/css/chatbot.css */
.chatbot-toggle {
    background: linear-gradient(135deg, #your-color1 0%, #your-color2 100%);
}
```

### Thêm phản hồi mới:
```php
// ChatBotController.php
if (preg_match('/\b(từ khóa mới)\b/', $message)) {
    return "Phản hồi tương ứng";
}
```

## 🐛 TROUBLESHOOTING

### ChatBot không hiển thị:
1. Kiểm tra CSS/JS đã load
2. Xem console browser
3. Đảm bảo không phải trang admin

### Không gửi được tin nhắn:
1. Kiểm tra CSRF token
2. Xem routes đã khai báo
3. Kiểm tra database connection

### Debug:
```javascript
// Thêm vào chatbot.js
console.log('ChatBot initialized');
console.log('Session ID:', this.sessionId);
```

## 📊 ANALYTICS

### Thống kê chat:
```sql
-- Tin nhắn theo ngày
SELECT DATE(created_at) as date, COUNT(*) as messages
FROM chat_messages 
WHERE sender = 'user'
GROUP BY DATE(created_at);

-- Từ khóa phổ biến
SELECT message, COUNT(*) as count
FROM chat_messages 
WHERE sender = 'user'
GROUP BY message
ORDER BY count DESC;
```

---

## ✅ STATUS

🎉 **CHATBOT AI ĐÃ SẴNG SÀNG!**

- [x] Database setup
- [x] Controllers & Models
- [x] Routes
- [x] CSS & JavaScript
- [x] Layout integration
- [x] Pattern matching
- [x] Response logic
- [x] Session management

**Truy cập trang client để test ChatBot!** 🚀
