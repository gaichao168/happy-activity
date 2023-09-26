<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateActivityUserScoreLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_user_score_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity_id')->index()->comment('活动id');
            $table->unsignedBigInteger('activity_user_id')->index()->comment('活动参与人员id');
            $table->unsignedBigInteger('activity_user_score_id')->index()->comment('活动参与人员分数id');
            $table->unsignedTinyInteger('score')->default(0)->comment('分数');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_user_score_log');
    }
}
