<?php

test("can list tags",function(){

    $response = $this->get("api/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        "categories" => [
            "*" => [
                'name',
            ],
        ],
            
    ]);
});

test("can add Tag", function(){
    $tag = [
       "name" => "yara"
    ];

    $responce = $this->post("api/categories",$tag);
    $responce->assertStatus(200);
    $tag = $responce->json('categories');

    $this->assertDatabaseHas('categories',['name' => $tag['name']]);

});