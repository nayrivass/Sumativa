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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->string("descripcion",200);
            $table->string("talla",5);
            $table->string("color",60);
            $table->decimal("precio",8,2);
            $table->integer("stock");
            $table->string("estado",1);
            $table->unsignedBigInteger("clasificacion_id");
            $table->foreign("clasificacion_id")->references("id")->on("clasificaciones");
            $table->unsignedBigInteger("categoria_id");
            $table->foreign("categoria_id")->references("id")->on("categorias");
            $table->unsignedBigInteger("marca_id");
            $table->foreign("marca_id")->references("id")->on("marcas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
