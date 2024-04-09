<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\DatabaseCommonTrait;

class CreateUserTable extends Migration
{
    use DatabaseCommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50)->unique();
            $table->string('password', 255);
            $table->string('name', 50);
            $table->tinyInteger('user_flg')->default(1);
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $this->commonColumns($table);
            $this->commonCharset($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
