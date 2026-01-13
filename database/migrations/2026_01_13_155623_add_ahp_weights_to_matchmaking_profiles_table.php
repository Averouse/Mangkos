<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matchmaking_profiles', function (Blueprint $table) {
            $table->json('ahp_weights')->nullable()->after('preferences');
        });
    }

    public function down()
    {
        Schema::table('matchmaking_profiles', function (Blueprint $table) {
            $table->dropColumn('ahp_weights');
        });
    }
};
