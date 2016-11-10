<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', 'BookController@index')->name('books.index');
Route::get('/books/{title}', 'BookController@show') -> name('books.show');
Route::get('books/{title}/edit', 'BookController@edit') -> name('books.edit');

Route::get('/books/create', function(){
    $view = '<form method = "POST" action="/books/create">';
    $view .= csrf_field();
    $view .= '<input type ="text" name= "title">';
    $view .= '<input type ="submit">';
    $view .= '</form>';

    return $view;
});

Route::post('/books/create', function(){
    dd(Request::all());
});

Route::get('/books/show/{title?}', function($title){

      if($title == ''){
        return "? means the title parameter is optional";
      }

    return 'Results for the book: ' .$title;
})->name('books.show'); //creates an alias for the url /books/show/{title?}

Route::get('/books/{category?}/{title?}', function($category='', $title=''){

      if($title == ''){
        return "You did not include a title.";
      }

    return 'Results for the book: ' .$title;
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(config('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    /*
    The following line will output your MySQL credentials.
    Uncomment it only if you're having a hard time connecting to the database and you
    need to confirm your credentials.
    When you're done debugging, comment it back out so you don't accidentally leave it
    running on your live server, making your credentials public.
    */
    //print_r(config('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    }
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});

if(App::environment('local')) {

    Route::get('/drop', function() {

        DB::statement('DROP database foobooks');
        DB::statement('CREATE database foobooks');

        return 'Dropped foobooks; created foobooks.';
    });

};
