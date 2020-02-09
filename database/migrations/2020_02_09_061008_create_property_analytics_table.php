<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Property;
use App\AnalyticType;

class CreatePropertyAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_analytics', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamps();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('analytic_type_id');
            $table->text('value')->nullable();

            // FK
            $table
                ->foreign('property_id')
                ->references('id')
                ->on((new Property)->getTable());

            $table
                ->foreign('analytic_type_id')
                ->references('id')
                ->on((new AnalyticType)->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_analytics');
    }
}
