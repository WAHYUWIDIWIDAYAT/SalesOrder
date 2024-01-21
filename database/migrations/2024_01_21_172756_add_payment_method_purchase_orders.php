<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
            $table->string('payment_method')->after('total')->nullable();
            $table->string('payment_status')->after('payment_method')->nullable();
            $table->string('payment_proof')->after('payment_status')->nullable();
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
            Schema::table('purchase_orders', function (Blueprint $table) {
                //
                $table->dropColumn('payment_method');
                $table->dropColumn('payment_status');
                $table->dropColumn('payment_proof');
            });
    }
}
