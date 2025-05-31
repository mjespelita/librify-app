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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('dateofbirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('maritalstatus')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergencycontact')->nullable();
            $table->string('employeeid')->nullable();
            $table->string('jobtitle')->nullable();
            $table->string('department')->nullable();
            $table->string('sss')->nullable();
            $table->string('pagibig')->nullable();
            $table->string('philhealth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('middlename');
            $table->dropColumn('lastname');
            $table->dropColumn('dateofbirth');
            $table->dropColumn('gender');
            $table->dropColumn('nationality');
            $table->dropColumn('maritalstatus');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('emergencycontact');
            $table->dropColumn('employeeid');
            $table->dropColumn('jobtitle');
            $table->dropColumn('department');
            $table->dropColumn('sss');
            $table->dropColumn('pagibig');
            $table->dropColumn('philhealth');
        });
    }
};
