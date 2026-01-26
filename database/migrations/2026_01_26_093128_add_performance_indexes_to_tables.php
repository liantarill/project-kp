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
        // Add indexes to attendances table for better query performance
        Schema::table('attendances', function (Blueprint $table) {
            // Composite index for user_id and date (most common query pattern)
            $table->index(['user_id', 'date'], 'idx_attendances_user_date');
            
            // Index on status for filtering
            $table->index('status', 'idx_attendances_status');
            
            // Index on created_at for sorting and date range queries
            $table->index('created_at', 'idx_attendances_created_at');
            
            // Index on date for general date queries
            $table->index('date', 'idx_attendances_date');
        });

        // Add indexes to users table for better query performance
        Schema::table('users', function (Blueprint $table) {
            // Index on status for filtering active/pending users
            $table->index('status', 'idx_users_status');
            
            // Index on role for role-based queries
            $table->index('role', 'idx_users_role');
            
            // Composite index for institution and department filtering
            $table->index(['institution_id', 'department_id'], 'idx_users_institution_department');
            
            // Index on start_date and end_date for date range queries
            $table->index('start_date', 'idx_users_start_date');
            $table->index('end_date', 'idx_users_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('idx_attendances_user_date');
            $table->dropIndex('idx_attendances_status');
            $table->dropIndex('idx_attendances_created_at');
            $table->dropIndex('idx_attendances_date');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_status');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_institution_department');
            $table->dropIndex('idx_users_start_date');
            $table->dropIndex('idx_users_end_date');
        });
    }
};
