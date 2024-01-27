<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add status to purchase order
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('total');
            $table->string('delivery_code')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        //drop status and delivery code from purchase order
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('delivery_code');
        });
    }
}
