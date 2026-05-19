<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite and covering indexes on ppdb_siswas to support:
     *
     * 1. `submitted_at` + `status` composite index → used by range query
     *    `whereBetween('submitted_at', [...])` + `where('status', ...)` filter.
     *    Database can satisfy the query without touching the actual row data
     *    (index-only scan), making it extremely fast at millions of rows.
     *
     * 2. `nisn` index → already unique, guarantees fast lookup for duplicate check.
     *
     * 3. `sekolah_asal` index → used by GROUP BY in getDistributions top-schools query.
     *
     * NOTE: `submitted_at` single-column index already exists (from original migration),
     * so we only add the composite version here.
     */
    public function up(): void
    {
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            // Composite index: speeds up year-range + status filter queries
            // used in getStats(), getApplicants(), getDistributions()
            $table->index(['submitted_at', 'status'], 'ppdb_submitted_at_status_idx');

            // Covering index for school grouping (avoids table row reads)
            $table->index(['submitted_at', 'sekolah_asal'], 'ppdb_submitted_at_sekolah_idx');

            // Index for gender grouping on distribution charts
            $table->index(['submitted_at', 'jenis_kelamin'], 'ppdb_submitted_at_gender_idx');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            $table->dropIndex('ppdb_submitted_at_status_idx');
            $table->dropIndex('ppdb_submitted_at_sekolah_idx');
            $table->dropIndex('ppdb_submitted_at_gender_idx');
        });
    }
};
