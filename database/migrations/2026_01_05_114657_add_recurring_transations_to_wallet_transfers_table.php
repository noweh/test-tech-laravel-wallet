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
        if (!Schema::hasColumn('wallet_transfers', 'type')) {
            Schema::table('wallet_transfers', function (Blueprint $table) {
                $table->enum('type', ['once', 'recurring'])->default('once');
                $table->integer('recurring_frequency')->nullable();
                $table->date('recurring_start_date')->nullable();
                $table->date('recurring_end_date')->nullable();
            });
        };
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('wallet_transfers', 'type')) {
            Schema::table('wallet_transfers', function (Blueprint $table) {
                $table->dropColumn('type');
                $table->dropColumn('recurring_frequency');
                $table->dropColumn('recurring_start_date');
                $table->dropColumn('recurring_end_date');
            });
        }
    }
};
