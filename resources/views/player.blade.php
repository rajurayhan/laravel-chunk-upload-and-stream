@extends('master')


@section('content')
    <div class="row player">
        <div class="col-lg-12">
            <h3>{{ $title }}</h3>
            <!-- <video id='my-video' class='video-js' controls preload='auto' width='640' height='264' poster="{{ asset('thumbs/'.$vid->thumbnail) }}" data-setup='{}'>
                <source src="{{ route('stream', $video) }}" type="{{$mime}}" />
                    <p class='vjs-no-js'>
                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                        <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>
                    </p>
            </video> -->
            <video id="player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" height="auto" width="100%" poster="{{ asset('thumbs/'.$vid->thumbnail) }}">
                <source src="{{ route('stream', $video) }}" type="{{$mime}}" />
            </video>
        </div>
    </div>

    <h4 style="margin-top: 25px;">You may also Watch - </h4>
    <div class="row">
        @foreach($videos as $vido)
            <div class="col-sm-4">
                <a href="{{ route('play', $vido->id) }}">
                    <img src="{{ asset('thumbs/'.$vido->thumbnail) }}" class="img img-thumbnail">
                    <h5>{{ $vido->name }}</h5>   
                </a>
            </div>
        @endforeach
    </div>

@endsection

@section('footer')
    <script>
        $(document).ready(function () {
            // Handler for .ready() called.
            $('html, body').animate({
                scrollTop: $('.player').offset().top
            }, 'slow');
        });
    </script>
@endsection
