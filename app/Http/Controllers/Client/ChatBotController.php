<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\Product;

class ChatBotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');
        $userId = Auth::id();

        // Save user message
        ChatMessage::create([
            'user_id' => $userId,
            'message' => $userMessage,
            'is_bot' => false
        ]);

        // Generate bot response
        $botResponse = $this->generateBotResponse($userMessage);

        // Save bot response
        ChatMessage::create([
            'user_id' => $userId,
            'message' => $botResponse,
            'is_bot' => true
        ]);

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
    }

    private function generateBotResponse($message)
    {
        $message = strtolower($message);

        // Search for books
        if (strpos($message, 'tìm') !== false || strpos($message, 'sách') !== false) {
            $keywords = explode(' ', $message);
            $products = Product::where(function($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 2) {
                        $query->orWhere('name', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                    }
                }
            })->take(3)->get();

            if ($products->count() > 0) {
                $response = "Tôi tìm thấy một số cuốn sách phù hợp:\n\n";
                foreach ($products as $product) {
                    $response .= "📚 {$product->name}\n";
                    $response .= "💰 " . number_format($product->price) . "đ\n";
                    $response .= "🔗 /products/{$product->id}\n\n";
                }
                return $response;
            }
        }

        // Greetings
        if (strpos($message, 'xin chào') !== false || strpos($message, 'hello') !== false) {
            return "Xin chào! Tôi là trợ lý AI của BookStore. Tôi có thể giúp bạn tìm sách, tư vấn mua hàng. Bạn cần hỗ trợ gì?";
        }

        // Default responses
        $responses = [
            "Tôi hiểu bạn đang cần tìm hiểu về sách. Bạn có thể nói cụ thể hơn về thể loại hoặc tên sách bạn quan tâm không?",
            "Chúng tôi có rất nhiều đầu sách hay. Bạn thích đọc thể loại gì? Tiểu thuyết, kỹ năng sống, hay sách chuyên môn?",
            "Để tôi có thể tư vấn tốt hơn, bạn có thể cho tôi biết ngân sách và sở thích đọc của bạn không?"
        ];

        return $responses[array_rand($responses)];
    }
}