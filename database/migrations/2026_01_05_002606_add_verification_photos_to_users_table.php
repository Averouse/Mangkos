<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_card_photo')->nullable(); // ID card only
            $table->string('selfie_with_id_photo')->nullable(); // Person holding ID card
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id_card_photo', 'selfie_with_id_photo']);
        });
    }
};
