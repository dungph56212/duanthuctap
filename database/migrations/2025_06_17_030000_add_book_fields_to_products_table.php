<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('author')->nullable()->after('name');
            $table->string('publisher')->nullable()->after('author');
            $table->string('isbn')->nullable()->after('publisher');
            $table->integer('pages')->nullable()->after('isbn');
            $table->year('publish_year')->nullable()->after('pages');
            $table->string('language', 10)->default('vi')->after('publish_year');
            $table->integer('view_count')->default(0)->after('sort_order');
            $table->integer('sold_count')->default(0)->after('view_count');
            
            // Rename stock_quantity to stock for easier use
            $table->renameColumn('stock_quantity', 'stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'author',
                'publisher', 
                'isbn',
                'pages',
                'publish_year',
                'language',
                'view_count',
                'sold_count'
            ]);
            
            $table->renameColumn('stock', 'stock_quantity');
        });
    }
};
