@extends('master')


@section('content')

    <section>
        <ul>
            @foreach($videos as $vid)
                <li><a href="{{ route('player', $vid->id) }}">{{ $vid->name }}</a></li>
            @endforeach
        </ul>
    </section>
    
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
                <input type="name" class="form-control" id="name" name="name">
            </div>
            <button type="button" id="submitBtn" class="btn btn-info">Submit</button>
        </div>
        </form>
    </section>


@endsection

<script type="text/javascript">
    
</script>