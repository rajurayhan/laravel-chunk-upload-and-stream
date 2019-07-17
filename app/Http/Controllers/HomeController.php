<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Helpers\VideoStream;
use App\Videos;
use Log;
use Thumbnail;
use FFMpeg;
use Image;


class HomeController extends Controller {
    

	public function index() {
        $vidObj     = new Videos();
        $videos     = $vidObj->take(9)->get();

		return view('index', compact('videos'));
	}

    public function play($id)
    {
        $vidObj     = new Videos();
        $vid        = $vidObj->findorfail($id);

        $video = $vid->file;
        $mime = $vid->fileType;
        $title = $vid->name;

        $videos     = $vidObj->where('id', '!=', $vid->id)->take(9)->get();

        // exit($videos);

        // return $videos;

        return view('player')->with(compact('video', 'mime', 'title', 'vid', 'videos'));
    }

    public function stream($filename)
    {
        $videosDir = storage_path('app/uploads/');
        if (file_exists($filePath = $videosDir."/".$filename)) {
            $stream = new VideoStream($filePath);
            return response()->stream(function() use ($stream) {
                $stream->start();
            });
        }
        return response("File doesn't exists", 404);
    }

    // public function helper() {

    //     $video_path = storage_path('app/uploads/EndGame.mp4');
    //     $stream = new VideoStream($video_path);
    //     //$stream->start(); 
    //     $x =  $stream->start(); 
    //     return $stream;
    // }

	public function upload(Request $request) {
		// create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success
        if ($receiver->isUploaded()) {
            // receive the file
            $save = $receiver->receive();
            // check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                // save the file and return any response you need
                $fileObj        = $this->saveFile($save->getFile());
                // $fileData       = json_decode($fileObj);
                Log::useDailyFiles(storage_path().'/logs/upload.log');
                Log::info($fileObj);

                $name           = $request->name;
                $ip             = $request->ip;
                $fileName       = $fileObj['file'];
                $fileType       = $fileObj['mime'];

                $image = $request->file('thumb');
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                $path = ('images/' . $filename);
                Image::make($image->getRealPath())->save($path);

                $videoObj   = new Videos();

                //$thumbnail      = $this->getThumnail($fileName,$fileObj['path']);
                // $duration       = $this->getDuration($fileObj['path']);

                $videoObj->name             = $name; 
                $videoObj->ip               = $ip;         
                $videoObj->file             = $fileName; 
                $videoObj->fileType         = $fileType; 
                // $videoObj->thumbnail        = $thumbnail;
                $videoObj->image            = $path; 
                $videoObj->thumbnail        = $path;

                $videoObj->save();

                // return $this->saveFile($save->getFile());
                return response()->json($fileObj);
            } else {
                // we are in chunk mode, lets send the current progress
                /** @var AbstractHandler $handler */
                $handler = $save->handler();
                return response()->json([
                    "done" => $handler->getPercentageDone(),
                ]);
            }
        } else {
            throw new UploadMissingFileException();
        }
	}

	private function saveFile(UploadedFile $file) {
        $fileName = $file->getClientOriginalName();//rename

        $path = storage_path("app/uploads");
        // move the file name
        $file->move($path, $fileName);

        $response   = [
                        'path' => $path.'/'.$fileName,
                        'file' => $fileName, 
                        'mime' => mime_content_type($path.'/'.$fileName),
                    ];

        return $response;
    }

    private function getThumnail($fileName, $vedioFile)
    {
        
        $path = public_path("thumbs/");
        $fileName   = $fileName.'_thumb.jpg';

        $thumbnail_status = Thumbnail::getThumbnail($vedioFile,$path,$fileName,5);

        if($thumbnail_status){
            return $fileName;
        }
        else{
            return 'NULL';
        }
        
    }



}
