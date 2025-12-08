<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add performance indexes to frequently queried columns
     */
    public function up(): void
    {
        // Sales table indexes
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!$this->hasIndex('sales', 'sales_branch_id_status_index')) {
                    $table->index(['branch_id', 'status'], 'sales_branch_id_status_index');
                }
                if (!$this->hasIndex('sales', 'sales_customer_id_due_date_index')) {
                    $table->index(['customer_id', 'due_date'], 'sales_customer_id_due_date_index');
                }
                if (!$this->hasIndex('sales', 'sales_created_at_index')) {
                    $table->index('created_at');
                }
            });
        }

        // Products table indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!$this->hasIndex('products', 'products_branch_id_status_index')) {
                    $table->index(['branch_id', 'is_active'], 'products_branch_id_status_index');
                }
                if (!$this->hasIndex('products', 'products_sku_index')) {
                    $table->index('sku');
                }
                if (!$this->hasIndex('products', 'products_category_id_index')) {
                    $table->index('category_id');
                }
            });
        }

        // Stock movements table indexes
        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                if (!$this->hasIndex('stock_movements', 'stock_movements_product_warehouse_index')) {
                    $table->index(['product_id', 'warehouse_id'], 'stock_movements_product_warehouse_index');
                }
                if (!$this->hasIndex('stock_movements', 'stock_movements_created_at_index')) {
                    $table->index('created_at');
                }
            });
        }

        // Purchases table indexes
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                if (!$this->hasIndex('purchases', 'purchases_branch_id_status_index')) {
                    $table->index(['branch_id', 'status'], 'purchases_branch_id_status_index');
                }
                if (!$this->hasIndex('purchases', 'purchases_supplier_id_index')) {
                    $table->index('supplier_id');
                }
            });
        }

        // Rental contracts table indexes
        if (Schema::hasTable('rental_contracts')) {
            Schema::table('rental_contracts', function (Blueprint $table) {
                if (!$this->hasIndex('rental_contracts', 'rental_contracts_status_index')) {
                    $table->index('status');
                }
                if (!$this->hasIndex('rental_contracts', 'rental_contracts_start_end_date_index')) {
                    $table->index(['start_date', 'end_date'], 'rental_contracts_start_end_date_index');
                }
            });
        }

        // Rental invoices table indexes
        if (Schema::hasTable('rental_invoices')) {
            Schema::table('rental_invoices', function (Blueprint $table) {
                if (!$this->hasIndex('rental_invoices', 'rental_invoices_status_due_date_index')) {
                    $table->index(['status', 'due_date'], 'rental_invoices_status_due_date_index');
                }
                if (!$this->hasIndex('rental_invoices', 'rental_invoices_tenant_id_index')) {
                    $table->index('tenant_id');
                }
            });
        }

        // Journal entries table indexes
        if (Schema::hasTable('journal_entries')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                if (!$this->hasIndex('journal_entries', 'journal_entries_date_index')) {
                    $table->index('date');
                }
                if (!$this->hasIndex('journal_entries', 'journal_entries_status_index')) {
                    $table->index('status');
                }
            });
        }

        // HR Employees table indexes
        if (Schema::hasTable('hr_employees')) {
            Schema::table('hr_employees', function (Blueprint $table) {
                if (!$this->hasIndex('hr_employees', 'hr_employees_branch_id_status_index')) {
                    $table->index(['branch_id', 'is_active'], 'hr_employees_branch_id_status_index');
                }
            });
        }

        // Attendances table indexes
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!$this->hasIndex('attendances', 'attendances_employee_date_index')) {
                    $table->index(['employee_id', 'date'], 'attendances_employee_date_index');
                }
            });
        }

        // Tickets table indexes
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (!$this->hasIndex('tickets', 'tickets_status_priority_index')) {
                    $table->index(['status', 'priority'], 'tickets_status_priority_index');
                }
                if (!$this->hasIndex('tickets', 'tickets_assigned_to_index')) {
                    $table->index('assigned_to');
                }
            });
        }

        // Bank accounts table indexes
        if (Schema::hasTable('bank_accounts')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                if (!$this->hasIndex('bank_accounts', 'bank_accounts_branch_id_active_index')) {
                    $table->index(['branch_id', 'is_active'], 'bank_accounts_branch_id_active_index');
                }
            });
        }

        // Audit logs table indexes
        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                if (!$this->hasIndex('audit_logs', 'audit_logs_user_id_created_index')) {
                    $table->index(['user_id', 'created_at'], 'audit_logs_user_id_created_index');
                }
                if (!$this->hasIndex('audit_logs', 'audit_logs_auditable_index')) {
                    $table->index(['auditable_type', 'auditable_id'], 'audit_logs_auditable_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sales
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropIndex('sales_branch_id_status_index');
                $table->dropIndex('sales_customer_id_due_date_index');
                $table->dropIndex(['created_at']);
            });
        }

        // Products
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_branch_id_status_index');
                $table->dropIndex(['sku']);
                $table->dropIndex(['category_id']);
            });
        }

        // Stock movements
        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->dropIndex('stock_movements_product_warehouse_index');
                $table->dropIndex(['created_at']);
            });
        }

        // Purchases
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropIndex('purchases_branch_id_status_index');
                $table->dropIndex(['supplier_id']);
            });
        }

        // Rental contracts
        if (Schema::hasTable('rental_contracts')) {
            Schema::table('rental_contracts', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropIndex('rental_contracts_start_end_date_index');
            });
        }

        // Rental invoices
        if (Schema::hasTable('rental_invoices')) {
            Schema::table('rental_invoices', function (Blueprint $table) {
                $table->dropIndex('rental_invoices_status_due_date_index');
                $table->dropIndex(['tenant_id']);
            });
        }

        // Journal entries
        if (Schema::hasTable('journal_entries')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropIndex(['date']);
                $table->dropIndex(['status']);
            });
        }

        // HR Employees
        if (Schema::hasTable('hr_employees')) {
            Schema::table('hr_employees', function (Blueprint $table) {
                $table->dropIndex('hr_employees_branch_id_status_index');
            });
        }

        // Attendances
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropIndex('attendances_employee_date_index');
            });
        }

        // Tickets
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropIndex('tickets_status_priority_index');
                $table->dropIndex(['assigned_to']);
            });
        }

        // Bank accounts
        if (Schema::hasTable('bank_accounts')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->dropIndex('bank_accounts_branch_id_active_index');
            });
        }

        // Audit logs
        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropIndex('audit_logs_user_id_created_index');
                $table->dropIndex('audit_logs_auditable_index');
            });
        }
    }

    /**
     * Check if index exists.
     */
    private function hasIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $doctrineSchemaManager = $connection->getDoctrineSchemaManager();
        $doctrineTable = $doctrineSchemaManager->listTableDetails($table);
        
        return $doctrineTable->hasIndex($index);
    }
};
