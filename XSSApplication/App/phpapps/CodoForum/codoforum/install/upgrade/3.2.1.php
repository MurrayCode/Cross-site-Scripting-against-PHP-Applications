<?php

Schema::dropIfExists('b8_wordlist');
Schema::create('b8_wordlist', function($table) {

    $table->string('token', 255);
    $table->integer('count_ham')->nullable();
    $table->integer('count_spam')->nullable();
    $table->primary('token');
});

DB::table('b8_wordlist')->insert(array(
    array(
        'token' => 'b8*dbversion',
        'count_ham' => 3,
        'count_spam' => null
    ),
    array(
        'token' => 'b8*texts',
        'count_ham' => 0,
        'count_spam' => 0
    )
));
