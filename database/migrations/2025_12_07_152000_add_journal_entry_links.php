<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add journal entry link to sales
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('journal_entry_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->index('journal_entry_id');
        });

        // Add journal entry link to purchases
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('journal_entry_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->index('journal_entry_id');
        });

        // Add journal entry link to payrolls
        Schema::table('payrolls', function (Blueprint $table) {
            $table->foreignId('journal_entry_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->index('journal_entry_id');
        });

        // Add journal entry link to rental invoices
        Schema::table('rental_invoices', function (Blueprint $table) {
            $table->foreignId('journal_entry_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->index('journal_entry_id');
        });
    }

    public function down(): void
    {
        Schema::table('rental_invoices', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropIndex(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropIndex(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropIndex(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropIndex(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });
    }
};
