<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('admins') && !Schema::hasColumn('admins', 'role')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->string('role', 100)->nullable()->after('full_name');
            });
        }
        if (Schema::hasTable('employees') && !Schema::hasColumn('employees', 'job_title')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('job_title', 100)->nullable()->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admins') && Schema::hasColumn('admins', 'role')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'job_title')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('job_title');
            });
        }
    }
};
