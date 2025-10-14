<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('receipt_details', function (Blueprint $table) {
                $table->id();
                $table->integer('receipt_id');
                $table->foreign('receipt_id')->references('id')->on('receipts');
                $table->string('toPaid');
                $table->string('remain');
                $table->string('detailRemain');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('receipt_details');
        }
    };
