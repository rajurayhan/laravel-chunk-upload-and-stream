@extends('master')


@section('content')
    
    <section>
        <h1 id="try-it-out">Upload Video!</h1>

        <div class="progress" style="height: 20px; margin-bottom: 25px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 0%%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div id="dropzone">
            <!-- <form class="dropzone dz-clickable" id="file-upload"> -->
                <form class="dropzone dz-clickable" id="fileUpload">
                {{ csrf_field() }}
                <div class="dz-message">Drop files here or click to upload.
                    <br> <span class="note">(Input name and Click Upload)</span>
                </div>
            
        </div>
        <hr>

        <div id="details">
            <div class="form-group">
                <label for="name">Name :</label>
                <input type="text" class="form-control" id="name" name="name" required="required">
            </div>

            <div class="form-group">
                <label for="thumb">Thumb :</label>
                <input type="file" class="form-control" id="thumb" name="thumb" required="required">
            </div>
            <button type="submit" id="submitBtn" class="btn btn-info">Submit</button>
        </div>
        </form>
    </section>
    <section style="margin-top: 50px;">
        <h4>Recent Uploads</h4>
        <div class="row">
            @foreach($videos as $vid)
                <div class="col-sm-4">
                    <a href="{{ route('play', $vid->id) }}">
                        <img src="{{ asset('/'.$vid->thumbnail) }}" class="img img-thumbnail">
                        <h5>{{ $vid->name }}</h5>   
                    </a>
                </div>
            @endforeach
        </div>
    </section>


@endsection

@section('footer')
<script type="text/javascript">
    var uploadURL           = '{{ route("uploader") }}';
    var uploadCallBack      =  '{{ route("home") }}';
</script>
<script src="//vjs.zencdn.net/4.12/video.js"></script>
@endsection