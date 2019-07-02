<?php
use App\Videos;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/helper', 'HomeController@helper');

Route::post('/file-upload', 'HomeController@upload');

Route::get('/play/{id}', 'HomeController@play')->name('play');

Route::get('/stream/{filename}', 'HomeController@play')->name('stream');




Route::get('/play/{id}', function ($id) {
    $vidObj 	= new Videos();
    $vid 		= $vidObj->findorfail($id);

    $video = $vid->file;
    $mime = $vid->fileType;
    $title = $vid->name;
    return view('player')->with(compact('video', 'mime', 'title', 'vid'));
})->name('player');


Route::get('/video/{filename}', function ($filename) {
    $videosDir = storage_path('app/uploads/');
    if (file_exists($filePath = $videosDir."/".$filename)) {
        $stream = new \App\Helpers\VideoStream($filePath);
        return response()->stream(function() use ($stream) {
            $stream->start();
        });
    }
    return response("File doesn't exists", 404);
})->name('video');



