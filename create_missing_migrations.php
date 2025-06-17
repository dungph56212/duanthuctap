<?php

/**
 * Script to create missing migration files and structures
 */

// Create sold_count migration
$soldCountMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(\'products\', function (Blueprint $table) {
            if (!Schema::hasColumn(\'products\', \'sold_count\')) {
                $table->integer(\'sold_count\')->default(0)->after(\'stock\');
            }
        });
    }

    public function down(): void
    {
        Schema::table(\'products\', function (Blueprint $table) {
            $table->dropColumn(\'sold_count\');
        });
    }
};';

file_put_contents('database/migrations/2024_06_17_000001_add_sold_count_to_products_table.php', $soldCountMigration);

// Create VNPay migration
$vnpayMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(\'orders\', function (Blueprint $table) {
            if (!Schema::hasColumn(\'orders\', \'vnpay_transaction_id\')) {
                $table->string(\'vnpay_transaction_id\')->nullable()->after(\'status\');
                $table->string(\'vnpay_response_code\')->nullable()->after(\'vnpay_transaction_id\');
                $table->string(\'vnpay_payment_status\')->default(\'pending\')->after(\'vnpay_response_code\');
            }
        });
    }

    public function down(): void
    {
        Schema::table(\'orders\', function (Blueprint $table) {
            $table->dropColumn([\'vnpay_transaction_id\', \'vnpay_response_code\', \'vnpay_payment_status\']);
        });
    }
};';

file_put_contents('database/migrations/2024_06_17_000002_add_vnpay_fields_to_orders_table.php', $vnpayMigration);

// Create shipping fee removal migration
$shippingFeeMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(\'orders\', function (Blueprint $table) {
            if (Schema::hasColumn(\'orders\', \'shipping_fee\')) {
                $table->dropColumn(\'shipping_fee\');
            }
        });
    }

    public function down(): void
    {
        Schema::table(\'orders\', function (Blueprint $table) {
            $table->decimal(\'shipping_fee\', 10, 2)->default(0);
        });
    }
};';

file_put_contents('database/migrations/2024_06_17_000003_remove_shipping_fee_from_orders_table.php', $shippingFeeMigration);

// Create is_active migration
$isActiveMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(\'users\', function (Blueprint $table) {
            if (!Schema::hasColumn(\'users\', \'is_active\')) {
                $table->boolean(\'is_active\')->default(true)->after(\'role\');
            }
        });
    }

    public function down(): void
    {
        Schema::table(\'users\', function (Blueprint $table) {
            $table->dropColumn(\'is_active\');
        });
    }
};';

file_put_contents('database/migrations/2025_06_17_020000_add_is_active_to_users_table.php', $isActiveMigration);

// Create book fields migration
$bookFieldsMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(\'products\', function (Blueprint $table) {
            if (!Schema::hasColumn(\'products\', \'isbn\')) {
                $table->string(\'isbn\')->nullable()->after(\'sold_count\');
                $table->string(\'author\')->nullable()->after(\'isbn\');
                $table->string(\'publisher\')->nullable()->after(\'author\');
                $table->integer(\'publication_year\')->nullable()->after(\'publisher\');
                $table->integer(\'pages\')->nullable()->after(\'publication_year\');
                $table->string(\'language\')->default(\'vi\')->after(\'pages\');
            }
        });
    }

    public function down(): void
    {
        Schema::table(\'products\', function (Blueprint $table) {
            $table->dropColumn([\'isbn\', \'author\', \'publisher\', \'publication_year\', \'pages\', \'language\']);
        });
    }
};';

file_put_contents('database/migrations/2025_06_17_030000_add_book_fields_to_products_table.php', $bookFieldsMigration);

echo "✓ Created all missing migration files\n";
echo "✓ Ready to run migrations\n";
