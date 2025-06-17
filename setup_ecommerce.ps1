# Setup E-commerce System - Laravel
Write-Host "Creating E-commerce System..." -ForegroundColor Green

# Create additional migrations
Write-Host "Creating migrations..." -ForegroundColor Yellow

php artisan make:migration create_reviews_table
php artisan make:migration create_wishlists_table
php artisan make:migration create_addresses_table
php artisan make:migration create_shipping_methods_table
php artisan make:migration create_payment_methods_table
php artisan make:migration create_product_attributes_table
php artisan make:migration create_product_attribute_values_table
php artisan make:migration create_banners_table
php artisan make:migration create_newsletters_table
php artisan make:migration create_settings_table

# Create Models
Write-Host "Creating models..." -ForegroundColor Yellow

php artisan make:model Category
php artisan make:model Product
php artisan make:model Order
php artisan make:model OrderItem
php artisan make:model Cart
php artisan make:model Coupon
php artisan make:model Review
php artisan make:model Wishlist
php artisan make:model Address
php artisan make:model ShippingMethod
php artisan make:model PaymentMethod
php artisan make:model ProductAttribute
php artisan make:model ProductAttributeValue
php artisan make:model Banner
php artisan make:model Newsletter
php artisan make:model Setting

# Create Controllers
Write-Host "Creating controllers..." -ForegroundColor Yellow

# Admin Controllers
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/CategoryController --resource
php artisan make:controller Admin/ProductController --resource
php artisan make:controller Admin/OrderController --resource
php artisan make:controller Admin/CouponController --resource
php artisan make:controller Admin/UserController --resource
php artisan make:controller Admin/ReviewController --resource
php artisan make:controller Admin/BannerController --resource
php artisan make:controller Admin/SettingController --resource

# Client Controllers
php artisan make:controller HomeController
php artisan make:controller ProductController
php artisan make:controller CategoryController
php artisan make:controller CartController
php artisan make:controller CheckoutController
php artisan make:controller OrderController
php artisan make:controller WishlistController
php artisan make:controller ReviewController
php artisan make:controller AccountController
php artisan make:controller AddressController

# Auth Controllers
php artisan make:controller Auth/RegisterController
php artisan make:controller Auth/LoginController

# Create Seeders
Write-Host "Creating seeders..." -ForegroundColor Yellow

php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder UserSeeder
php artisan make:seeder CouponSeeder
php artisan make:seeder ShippingMethodSeeder
php artisan make:seeder PaymentMethodSeeder
php artisan make:seeder BannerSeeder
php artisan make:seeder SettingSeeder
php artisan make:seeder AdminUserSeeder

# Create Factories
Write-Host "Creating factories..." -ForegroundColor Yellow

php artisan make:factory CategoryFactory
php artisan make:factory ProductFactory
php artisan make:factory OrderFactory
php artisan make:factory OrderItemFactory
php artisan make:factory ReviewFactory
php artisan make:factory CouponFactory

# Create Requests
Write-Host "Creating form requests..." -ForegroundColor Yellow

php artisan make:request Admin/CategoryRequest
php artisan make:request Admin/ProductRequest
php artisan make:request Admin/CouponRequest
php artisan make:request CheckoutRequest
php artisan make:request ReviewRequest

# Create Resources
Write-Host "Creating API resources..." -ForegroundColor Yellow

php artisan make:resource CategoryResource
php artisan make:resource ProductResource
php artisan make:resource OrderResource
php artisan make:resource CartResource

# Create Middleware
Write-Host "Creating middleware..." -ForegroundColor Yellow

php artisan make:middleware AdminMiddleware
php artisan make:middleware CheckCartMiddleware

# Create Commands
Write-Host "Creating artisan commands..." -ForegroundColor Yellow

php artisan make:command CleanExpiredCoupons
php artisan make:command UpdateProductStock

# Create Jobs
Write-Host "Creating jobs..." -ForegroundColor Yellow

php artisan make:job SendOrderConfirmationEmail
php artisan make:job UpdateInventoryJob
php artisan make:job ProcessPaymentJob

# Create Notifications
Write-Host "Creating notifications..." -ForegroundColor Yellow

php artisan make:notification OrderPlacedNotification
php artisan make:notification OrderStatusUpdatedNotification
php artisan make:notification WelcomeEmailNotification

# Create Events
Write-Host "Creating events..." -ForegroundColor Yellow

php artisan make:event OrderPlaced
php artisan make:event ProductViewed
php artisan make:event UserRegistered

# Create Listeners
Write-Host "Creating listeners..." -ForegroundColor Yellow

php artisan make:listener SendOrderConfirmationEmail --event=OrderPlaced
php artisan make:listener UpdateProductViews --event=ProductViewed
php artisan make:listener SendWelcomeEmail --event=UserRegistered

# Install additional packages
Write-Host "Installing additional packages..." -ForegroundColor Yellow

composer require intervention/image
composer require laravel/sanctum
composer require spatie/laravel-permission
composer require barryvdh/laravel-dompdf

# Publish package configs
Write-Host "Publishing package configurations..." -ForegroundColor Yellow

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

Write-Host "E-commerce system setup completed!" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Update migration files with proper schema" -ForegroundColor White
Write-Host "2. Update model relationships" -ForegroundColor White
Write-Host "3. Implement controller logic" -ForegroundColor White
Write-Host "4. Create views" -ForegroundColor White
Write-Host "5. Run: php artisan migrate" -ForegroundColor White
Write-Host "6. Run: php artisan db:seed" -ForegroundColor White
