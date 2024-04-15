<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait DatabaseCommonTrait {
    public function commonColumns(Blueprint $table) {
        $table->tinyInteger('del_flg')->default(0);
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');
        $table->foreignId('deleted_by')->nullable()->constrained('users');
        $table->timestamps();
        $table->softDeletes();
    }

    public function commonCharset(Blueprint $table) {
        $table->charset = 'utf8';
        $table->collation = 'utf8_general_ci';
    }
}
