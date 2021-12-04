<div class="row mt30">
    @empty(!$videos_data)
        @foreach ($videos_data as $key => $value)
        <div class="col-md-3">
            <div class="email-template-box">
                <div class="email-template-box-img">
                    <img src="{{ Config::get('app.url') . VIDEO_THUMBNAILS_PATH . '/' . $value['thumbnail_name'] }}" width="278" height="275"/>
                    <div class="email-template-box-img-hoverbox">
                        <a target="_blank" href="{{ $value['title'] }}">Preview</a>
                    </div>
                </div>
                <div class="email-template-box-textbox">
                    <div class="row">
                        <div class="col-md-10">
                            <h1>{{ $value['title'] }} - {{ $classes[$value['get_class_subject']['class_id']] }}({{ $subjects[$value['get_class_subject']['subject_id']] }})</h1>
                        </div>
                        <div class="col-md-2">
                            <div class="dropdown email-template-box-textbox-dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href=""><i class="fas fa-edit" aria-hidden="true"></i> Edit Video Info</a>
                                    <a class="dropdown-item" href=""><i class="fas fa-trash-alt" aria-hidden="true"></i> Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endempty
</div>
<div class="row mt30">
    <div class="col-md-3 text-left">
        {{ $videos->links() }}
    </div>
</div>
