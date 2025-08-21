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
        Schema::create('rusaks', function (Blueprint $table) {
            $table->id();
            $table->string('id_masterpengembalian');
            $table->string('tanggal');
            // $table->string('qty');
            // $table->string('ketkerusakan');
            $table->string('bukti');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rusaks');
    }
};
