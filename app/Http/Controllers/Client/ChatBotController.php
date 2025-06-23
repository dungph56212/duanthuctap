<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ChatMessage;
use App\Models\Product;

class ChatBotController extends Controller
{    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');
        $userId = Auth::id() ?? 0; // Allow guest users

        // Save user message only if user is logged in
        if ($userId > 0) {
            ChatMessage::create([
                'user_id' => $userId,
                'message' => $userMessage,
                'is_bot' => false
            ]);
        }

        // Generate bot response
        $botResponse = $this->generateBotResponse($userMessage);

        // Save bot response only if user is logged in
        if ($userId > 0) {
            ChatMessage::create([
                'user_id' => $userId,
                'message' => $botResponse,
                'is_bot' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'response' => $botResponse
        ]);
    }

    public function getChatHistory()
    {
        $userId = Auth::id();
        
        $messages = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }    private function generateBotResponse($message)
    {
        $message = strtolower($message);

        // Enhanced search for books - more flexible keyword matching
        if (strpos($message, 'tìm') !== false || 
            strpos($message, 'sách') !== false || 
            strpos($message, 'tên') !== false ||
            strpos($message, 'có') !== false ||
            strpos($message, 'kiếm') !== false) {
            
            // Extract keywords from message
            $keywords = preg_split('/\s+/', $message);
            $searchTerms = [];
            
            // Remove common words and keep meaningful terms
            $stopWords = ['tìm', 'sách', 'có', 'không', 'gì', 'nào', 'về', 'của', 'cho', 'tôi', 'mình'];
            foreach ($keywords as $keyword) {
                if (strlen($keyword) > 2 && !in_array($keyword, $stopWords)) {
                    $searchTerms[] = $keyword;
                }
            }
            
            if (!empty($searchTerms)) {
                $products = Product::where('is_active', true)
                    ->where(function($query) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $query->orWhere('name', 'like', '%' . $term . '%')
                                  ->orWhere('description', 'like', '%' . $term . '%');
                        }
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();

                if ($products->count() > 0) {
                    $response = "🔍 Tôi tìm thấy " . $products->count() . " cuốn sách phù hợp:\n\n";
                    foreach ($products as $product) {
                        $response .= "📚 **{$product->name}**\n";
                        $response .= "💰 Giá: " . number_format($product->price) . "đ\n";
                        if ($product->description) {
                            $response .= "📝 " . Str::limit($product->description, 100) . "\n";
                        }
                        $response .= "🔗 Xem chi tiết: /products/{$product->id}\n\n";
                    }
                    $response .= "💡 Bạn có thể click vào link để xem chi tiết sản phẩm!";
                    return $response;
                } else {
                    return "😔 Xin lỗi, tôi không tìm thấy sách nào phù hợp với từ khóa \"" . implode(', ', $searchTerms) . "\". \n\n🔍 Bạn có thể thử:\n- Tìm theo tên tác giả\n- Tìm theo thể loại (tiểu thuyết, kỹ năng, khoa học...)\n- Hoặc duyệt qua danh mục sản phẩm của chúng tôi!";
                }
            }
        }

        // Category suggestions
        if (strpos($message, 'thể loại') !== false || strpos($message, 'danh mục') !== false) {
            return "📂 Chúng tôi có các thể loại sách sau:\n\n📖 Tiểu thuyết\n💼 Kỹ năng sống\n🔬 Khoa học\n📱 Công nghệ\n🎨 Nghệ thuật\n📚 Giáo dục\n\nBạn quan tâm đến thể loại nào?";
        }

        // Price inquiry
        if (strpos($message, 'giá') !== false || strpos($message, 'tiền') !== false) {
            return "💰 Giá sách tại BookStore rất đa dạng:\n\n📚 Sách giáo khoa: 50,000 - 200,000đ\n📖 Tiểu thuyết: 80,000 - 300,000đ\n💼 Sách kỹ năng: 100,000 - 500,000đ\n\nBạn có ngân sách cụ thể nào không? Tôi có thể tư vấn sách phù hợp!";
        }

        // Greetings
        if (strpos($message, 'xin chào') !== false || 
            strpos($message, 'hello') !== false ||
            strpos($message, 'chào') !== false) {
            return "👋 Xin chào! Tôi là BookBot - trợ lý AI của BookStore! \n\n🤖 Tôi có thể giúp bạn:\n📚 Tìm kiếm sách\n💡 Tư vấn sách hay\n📋 Thông tin về đơn hàng\n\nBạn cần hỗ trợ gì ạ?";
        }

        // Help
        if (strpos($message, 'giúp') !== false || strpos($message, 'hỗ trợ') !== false) {
            return "🆘 Tôi có thể giúp bạn:\n\n🔍 **Tìm kiếm**: \"Tìm sách về lập trình\"\n📚 **Tư vấn**: \"Sách nào hay cho người mới bắt đầu\"\n💰 **Giá cả**: \"Sách này giá bao nhiêu\"\n📦 **Đơn hàng**: \"Kiểm tra đơn hàng\"\n\nHãy nói cho tôi biết bạn cần gì nhé!";
        }

        // Default responses
        $responses = [
            "🤔 Tôi hiểu bạn đang tìm hiểu về sách. Bạn có thể nói cụ thể hơn không?\n\n💡 Ví dụ: \"Tìm sách tiểu thuyết\" hoặc \"Sách về lập trình\"",
            "📚 Chúng tôi có rất nhiều đầu sách hay! Bạn quan tâm đến thể loại nào?\n\n🔍 Hãy thử: \"Tìm sách kỹ năng sống\" hoặc \"Sách khoa học\"",
            "💬 Để tôi tư vấn tốt hơn, bạn có thể cho biết:\n📖 Thể loại yêu thích\n💰 Ngân sách\n🎯 Mục đích đọc"
        ];

        return $responses[array_rand($responses)];
    }
}