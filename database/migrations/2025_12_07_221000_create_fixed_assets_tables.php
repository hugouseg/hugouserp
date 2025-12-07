<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fixed Assets table
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->comment('Branch ID');
            $table->string('asset_code')->unique()->comment('Asset code');
            $table->string('name')->comment('Asset name');
            $table->text('description')->nullable()->comment('Description');
            $table->string('category')->comment('Asset category');
            $table->string('location')->nullable()->comment('Physical location');
            $table->date('purchase_date')->comment('Purchase date');
            $table->decimal('purchase_cost', 18, 4)->comment('Original purchase cost');
            $table->decimal('salvage_value', 18, 4)->default(0)->comment('Expected salvage/residual value');
            $table->integer('useful_life_years')->comment('Useful life in years');
            $table->integer('useful_life_months')->default(0)->comment('Additional months');
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'units_of_production'])->default('straight_line')->comment('Depreciation method');
            $table->decimal('depreciation_rate', 8, 4)->nullable()->comment('Rate for declining balance method');
            $table->decimal('accumulated_depreciation', 18, 4)->default(0)->comment('Total depreciation to date');
            $table->decimal('book_value', 18, 4)->comment('Current book value');
            $table->date('depreciation_start_date')->nullable()->comment('Date when depreciation starts');
            $table->date('last_depreciation_date')->nullable()->comment('Last depreciation calculation date');
            $table->string('status')->default('active')->comment('Status: active, disposed, sold, retired');
            $table->date('disposal_date')->nullable()->comment('Date of disposal');
            $table->decimal('disposal_amount', 18, 4)->nullable()->comment('Amount received on disposal');
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('Supplier ID');
            $table->string('serial_number')->nullable()->comment('Serial number');
            $table->string('model')->nullable()->comment('Model');
            $table->string('manufacturer')->nullable()->comment('Manufacturer');
            $table->date('warranty_expiry')->nullable()->comment('Warranty expiry date');
            $table->unsignedBigInteger('assigned_to')->nullable()->comment('Employee assigned to');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->json('meta')->nullable()->comment('Additional metadata');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['branch_id', 'status']);
            $table->index('category');
            $table->index('purchase_date');
        });

        // Depreciation Schedule/History table
        Schema::create('asset_depreciations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->comment('Fixed asset ID');
            $table->unsignedBigInteger('branch_id')->comment('Branch ID');
            $table->date('depreciation_date')->comment('Date of depreciation entry');
            $table->string('period')->comment('Period (e.g., 2025-01, 2025-Q1)');
            $table->decimal('depreciation_amount', 18, 4)->comment('Depreciation for this period');
            $table->decimal('accumulated_depreciation', 18, 4)->comment('Cumulative depreciation');
            $table->decimal('book_value', 18, 4)->comment('Book value after depreciation');
            $table->unsignedBigInteger('journal_entry_id')->nullable()->comment('Linked journal entry ID');
            $table->string('status')->default('calculated')->comment('Status: calculated, posted, reversed');
            $table->text('notes')->nullable()->comment('Notes');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('asset_id')->references('id')->on('fixed_assets')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['asset_id', 'depreciation_date']);
            $table->index(['branch_id', 'period']);
            $table->unique(['asset_id', 'period'], 'asset_period_unique');
        });

        // Asset Maintenance Log
        Schema::create('asset_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->comment('Fixed asset ID');
            $table->date('maintenance_date')->comment('Date of maintenance');
            $table->string('maintenance_type')->comment('Type: routine, repair, upgrade');
            $table->text('description')->comment('Maintenance description');
            $table->decimal('cost', 18, 4)->default(0)->comment('Maintenance cost');
            $table->unsignedBigInteger('vendor_id')->nullable()->comment('Vendor/supplier ID');
            $table->string('performed_by')->nullable()->comment('Technician/company name');
            $table->date('next_maintenance_date')->nullable()->comment('Next scheduled maintenance');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('asset_id')->references('id')->on('fixed_assets')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['asset_id', 'maintenance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_logs');
        Schema::dropIfExists('asset_depreciations');
        Schema::dropIfExists('fixed_assets');
    }
};
