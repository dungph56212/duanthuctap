<?php
// Debug admin users
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h1>Debug Admin Users</h1>";

try {
    $adminUsers = \App\Models\User::where('is_admin', true)->get();
    
    echo "<h2>Total Admin Users: " . $adminUsers->count() . "</h2>";
    
    if ($adminUsers->count() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Is Admin</th><th>Password Hash</th></tr>";
        
        foreach ($adminUsers as $user) {
            echo "<tr>";
            echo "<td>" . $user->id . "</td>";
            echo "<td>" . $user->name . "</td>";
            echo "<td>" . $user->email . "</td>";
            echo "<td>" . ($user->is_admin ? 'Yes' : 'No') . "</td>";
            echo "<td>" . substr($user->password, 0, 20) . "...</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No admin users found!</p>";
        echo "<p>Try running: <code>php artisan db:seed --class=UserSeeder</code></p>";
    }
    
    // Test password verification
    $testUser = \App\Models\User::where('email', 'tiendung08102005@gmail.com')->first();
    if ($testUser) {
        echo "<h2>Password Test for tiendung08102005@gmail.com</h2>";
        $testPassword = 'Dung08102005#';
        $isValid = \Illuminate\Support\Facades\Hash::check($testPassword, $testUser->password);
        echo "<p>Password 'Dung08102005#' is " . ($isValid ? '<span style="color: green;">VALID</span>' : '<span style="color: red;">INVALID</span>') . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
