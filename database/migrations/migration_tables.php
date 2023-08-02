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
        Schema::create('extension', function (Blueprint $table) {
            $table->integer('idExtension')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_extension', 50)->unique();
        });
        Schema::create('upload', function (Blueprint $table) {
            $table->integer('idUpload')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_upload', 255)->unique();
            $table->string('url_upload', 255);
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('idExtension')->length(11);
            $table->foreign('idExtension')->references('idExtension')->on('extension');
        });
        Schema::create('user', function (Blueprint $table) {
            $table->integer('idUser')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('email', 255)->unique();
            $table->string('password', 255);
        });
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('idProfile')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_profile', 255)->unique();
            $table->string('description_profile', 500);
            $table->string('number', 50);
            $table->string('birthday', 100);
            $table->integer('idUser')->length(11);
            $table->foreign('idUser')->references('idUser')->on('user');
        });
        Schema::create('type', function (Blueprint $table) {
            $table->integer('idType')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_type', 255)->unique();
            $table->string('description_type', 255);
            $table->TinyInteger('position_type')->default(0)->length(4);
        });
        Schema::create('usability', function (Blueprint $table) {
            $table->integer('idUsability')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_usability', 255)->unique();
            $table->string('description_usability', 255);
            $table->TinyInteger('position_usability')->default(0)->length(4);
        });
        Schema::create('file', function (Blueprint $table) {
            $table->integer('idFile')->length(11)->autoIncrement(); //autoIncrement entiende es primary
            $table->string('name_file', 255)->unique();
            $table->string('description_file', 500);
            $table->string('url_image', 255)->nullable();
            $table->string('url_video', 255)->nullable();
            $table->string('url_visit', 255)->nullable();
            $table->string('url_document', 255)->nullable();
            $table->string('url_download', 255)->nullable();
            $table->string('url_repository', 255)->nullable();
            $table->string('url_icon', 255)->nullable();
            $table->string('languaje', 255)->nullable();
            $table->TinyInteger('nivel')->nullable();
            $table->TinyInteger('position_file')->default(0)->length(4);
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('idUsability')->length(11);
            $table->integer('idType')->length(11);
            $table->integer('idUser')->length(11);
            $table->foreign('idUsability')->references('idUsability')->on('usability');
            $table->foreign('idType')->references('idType')->on('type');
            $table->foreign('idUser')->references('idUser')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extension');
        Schema::dropIfExists('upload');
        Schema::dropIfExists('user');
        Schema::dropIfExists('profile');
        Schema::dropIfExists('type');
        Schema::dropIfExists('usability');
        Schema::dropIfExists('file');
    }
};
