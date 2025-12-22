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

    Schema::create('users', function (Blueprint $table) {

        $table->id();

        $table->string('full_name'); // From your Fig 15 UI [cite: 632]

        $table->string('matric_id')->unique(); // From your Fig 15 UI [cite: 634]

        $table->string('email')->unique();

        $table->timestamp('email_verified_at')->nullable();

        $table->string('password');

       

        // From your ERD [cite: 555], we need to know if it's a Student, Teacher, or Admin

        $table->string('role')->default('student');



        $table->rememberToken();

        $table->timestamps();

    });

}



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('users');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('sessions');

    }

};