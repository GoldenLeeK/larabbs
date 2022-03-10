<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWechatOpenidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('wechat_openid')->unique()->nullable()->after('password');
            $table->string('wechat_unionid')->unique()->nullable()->after('wechat_openid');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wechat_openid');
            $table->dropColumn('wechat_unionid');
            $table->string('password')->nullable(false)->change();
        });
    }
}
