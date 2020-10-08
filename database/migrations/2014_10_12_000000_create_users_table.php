<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
// use DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('age');
            $table->string('gender');
            $table->timestamp('username_verified_at')->nullable();
            $table->string('avatar')->nullable($value = true);
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('avatar')->nullable($value = true);
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // });
        
        // Schema::create('roles', function (Blueprint $table) {
        //     $table->Increments('id')->unsigned();
        //     $table->integer('user_id')->unsigned();
        //     $table->string('role');

        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        // });

        Schema::create('categories', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->string('category_name');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        Schema::create('products', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->string('product_name');
            $table->integer('category_id')->unsigned();
            $table->longText('product_description');
            $table->integer('product_price');
            $table->string('product_image')->nullable($value = true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('status');
            $table->integer('total_price');
            $table->timestamp('delivered_at')->nullable($value = true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('quantity');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

		// DB::table('users')->insert(
        //     array(
        //         'id'=> 1,
        //         'username'=> 'admin',
        //         'name'=> 'admin admin',
        //         'age'=> '23',
        //         'gender' => 'Male',
        //         'password' => bcrypt('admin123')
        //     )
        // );
		// DB::table('roles')->insert(
        //     array(
        //         'id'=> 1,
        //         'user_id'=> 1,
        //         'role'=> 'admin'
        //     )
        // );
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
