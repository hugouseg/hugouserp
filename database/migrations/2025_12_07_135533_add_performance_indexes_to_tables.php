<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Performance Indexes Migration
 *
 * This migration adds missing indexes to improve query performance across
 * frequently-accessed tables and foreign key columns.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds indexes to:
     * - Foreign key columns for better join performance
     * - Status and date columns for common filtering operations
     * - Composite indexes for common query patterns
     */
    public function up(): void
    {
        // Add indexes to sales table for better query performance
        Schema::table('sales', function (Blueprint $table) {
            $table->index('status', 'sales_status_idx');
            $table->index('posted_at', 'sales_posted_at_idx');
            $table->index(['customer_id', 'created_at'], 'sales_cust_created_idx');
        });

        // Add indexes to sale_items table
        Schema::table('sale_items', function (Blueprint $table) {
            $table->index(['sale_id', 'product_id'], 'sale_items_sale_prod_idx');
            $table->index('product_id', 'sale_items_product_idx');
        });

        // Add indexes to purchases table
        Schema::table('purchases', function (Blueprint $table) {
            $table->index('status', 'purchases_status_idx');
            $table->index('posted_at', 'purchases_posted_at_idx');
            $table->index(['supplier_id', 'created_at'], 'purchases_supp_created_idx');
        });

        // Add indexes to purchase_items table
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->index(['purchase_id', 'product_id'], 'purchase_items_purch_prod_idx');
            $table->index('product_id', 'purchase_items_product_idx');
        });

        // Add indexes to stock_movements table for inventory tracking
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index(['product_id', 'branch_id', 'created_at'], 'stock_mv_prod_br_date_idx');
            $table->index(['warehouse_id', 'created_at'], 'stock_mv_wh_date_idx');
        });

        // Add indexes to audit_logs for better filtering
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['auditable_type', 'auditable_id'], 'audit_logs_auditable_idx');
            $table->index(['user_id', 'created_at'], 'audit_logs_user_date_idx');
            $table->index('event', 'audit_logs_event_idx');
        });

        // Add indexes to products for better search and filtering
        Schema::table('products', function (Blueprint $table) {
            $table->index('sku', 'products_sku_idx');
            $table->index('barcode', 'products_barcode_idx');
            $table->index(['branch_id', 'product_type'], 'products_br_type_idx');
        });

        // Add indexes to customers for search optimization
        Schema::table('customers', function (Blueprint $table) {
            $table->index('email', 'customers_email_idx');
            $table->index('phone', 'customers_phone_idx');
            $table->index(['branch_id', 'status'], 'customers_br_status_idx');
        });

        // Add indexes to suppliers
        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('email', 'suppliers_email_idx');
            $table->index('phone', 'suppliers_phone_idx');
            $table->index(['branch_id', 'status'], 'suppliers_br_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Removes all indexes added by the up() method.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('sales_status_idx');
            $table->dropIndex('sales_posted_at_idx');
            $table->dropIndex('sales_cust_created_idx');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropIndex('sale_items_sale_prod_idx');
            $table->dropIndex('sale_items_product_idx');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex('purchases_status_idx');
            $table->dropIndex('purchases_posted_at_idx');
            $table->dropIndex('purchases_supp_created_idx');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropIndex('purchase_items_purch_prod_idx');
            $table->dropIndex('purchase_items_product_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('stock_mv_prod_br_date_idx');
            $table->dropIndex('stock_mv_wh_date_idx');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_auditable_idx');
            $table->dropIndex('audit_logs_user_date_idx');
            $table->dropIndex('audit_logs_event_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_sku_idx');
            $table->dropIndex('products_barcode_idx');
            $table->dropIndex('products_br_type_idx');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_email_idx');
            $table->dropIndex('customers_phone_idx');
            $table->dropIndex('customers_br_status_idx');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('suppliers_email_idx');
            $table->dropIndex('suppliers_phone_idx');
            $table->dropIndex('suppliers_br_status_idx');
        });
    }
};
