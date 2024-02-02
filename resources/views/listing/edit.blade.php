@extends('layouts.master')

@section('title', __('Update Listing'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Update Listing') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Update Listing') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('listing.update', $post->id) }}" method="POST" enctype='multipart/form-data' id='form'>
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-9 col-xl-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update Listing') }}
                        </h4>

                        <!-- <button type="submit" class="btn btn-primary float-right">Save</button> -->
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Title') }}<span class="text-danger">*</span> <span class="text-success">(Prduct Name | Author | Edition | Publication ( Medium ) )</span></label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $post->title }}" autocomplete="title" autofocus placeholder="title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Description') }}<span class="text-danger">*</span><span class="text-danger"> ( Enter Detail Description without using 3rd party link)</span></label>
                                <textarea id="desc" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description" rows="10">{{ old('description') ?? $allInfo['desc'] }}</textarea>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail Price)</span></label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') ?? $allInfo['mrp']}}" autocomplete="mrp" autofocus placeholder="MRP">

                                @error('mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="selling_price" class="form-label">{{ __('Selling Price') }}<span class="text-danger">*</span></label>
                                <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') ?? $allInfo['selling']  }}" autocomplete="selling_price" autofocus placeholder="Selling Price">

                                @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="publication" class="form-label">{{ __('Publisher') }}<span class="text-danger">*</span></label>
                                <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') ?? $allInfo['publication'] }}" autocomplete="publication" autofocus placeholder="Publication">
                                <span class="error-message publication" style="color:red;"></span>

                                @error('publication')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="author_name" class="form-label">{{ __('Author Name') }}<span class="text-danger">*</span></label>
                                <input id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') ?? $allInfo['author_name'] }}" autocomplete="author_name" autofocus placeholder="Author name">
                                <span class="error-message author_name" style="color:red;"></span>

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') ?? $allInfo['edition']  }}" autocomplete="edition" autofocus placeholder="Edition">
                                <span class="error-message edition" style="color:red;"></span>

                                @error('edition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="label" class="form-label">{{ __('Category') }}<span class="text-danger">*</span><span class="text-danger"> ( Publication, 1 Category, 1 Tag, Others ) </span></label>
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple value="{{ old('label') }}" name="label[]">
                                @foreach($categories as $category)
                                <option value="{{ $category['term'] }}" @foreach($labels as $label) @if($category['term']==$label) selected @endif @endforeach>
                                    {{$category['term']}}
                                </option>
                                @endforeach
                            </select>

                            @error('label')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="sku" class="form-label">{{ __('SKU') }}<span class="text-danger">*</span><span class="text-danger"> ( Short Code ) </span></label>
                                <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') ?? $allInfo['sku'] }}" autocomplete="sku" autofocus placeholder="SKU">
                                <span class="error-message sku" style="color:red;"></span>

                                @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') ?? $allInfo['lang']}}" autocomplete="language" autofocus placeholder="Language">
                                <span class="error-message language" style="color:red;"></span>

                                @error('language')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                <input id="pages" type="text" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') ?? $allInfo['page_no'] }}" autocomplete="pages" autofocus placeholder="No. of Pages">
                                <span class="error-message pages" style="color:red;"></span>

                                @error('pages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="binding" class="form-label">{{ __('Binding Type') }}<span class="text-danger">*</span></label>

                                <select class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}">
                                    <option value="">--Select--</option>
                                    <option value="New" {{ $allInfo['binding'] == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Like New" {{ $allInfo['binding'] == 'Like New' ? 'selected' : '' }}>Like New</option>
                                    <option value="Old" {{ $allInfo['binding'] == 'Old' ? 'selected' : '' }}>Old</option>
                                </select>

                                <!-- <input id="binding" type="text" class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') ?? $allInfo['binding'] }}" autocomplete="binding" autofocus placeholder="Binding Type"> -->
                                <span class="error-message binding" style="color:red;"></span>

                                @error('binding')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="condition" class="form-label">{{ __('Condition') }}<span class="text-danger">*</span></label>
                                <select class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}">
                                    <option value="">--Select--</option>
                                    <option value="Hardcover" {{ $allInfo['condition'] == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                    <option value="Paperback" {{ $allInfo['condition'] == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                </select>
                                <!-- <input id="condition" type="text" class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') ?? $allInfo['condition'] }}" autocomplete="condition" autofocus placeholder="Condition"> -->
                                <span class="error-message condition" style="color:red;"></span>

                                @error('condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Insta Mojo URL') }}</label>
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') ?? $allInfo['url'] }}" autocomplete="url" autofocus placeholder="Insta Mojo Url">
                                <span class="error-message url" style="color:red;"></span>

                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-primary float-right">Update</button>
                            <!-- <button type="submit" class="btn btn-primary float-right" id='draft'>Save as Draft</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div id="fileInputContainer">
                                <div class="form-group">
                                    <label for="fileInput1">Main Images<span class="text-danger">*</span></label>
                                    <div class="form-group mb-0" @error('images') style="border: red 2px dotted;" @enderror>
                                        <input type="hidden" name="images[]" value={{ $allInfo['baseimg'] }} />
                                        <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="fileInput1" name="images[]" value={{ $allInfo['baseimg'] }} data-default-file={{$allInfo['baseimg']}} />
                                    </div>

                                    @error('images')
                                    <span class="invalid-feedback mt-2" style="display:block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div id='fileInputContainer'>
                                        @if($allInfo['multiple'] && count($allInfo['multiple']) != 1)
                                        <label for="fileInput1" class="mt-2">Preview Images<span class="text-danger">*</span></label><br>
                                        @foreach($allInfo['multiple'] as $key => $images)
                                        @if($key == 0) @continue; @endif
                                        <div class="input-group{{$key}}">
                                            <input type="hidden" name="multipleImages[]" value={{ $images }} />
                                            <div class="form-group mb-1" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                                <input data-default-file={{$images}} id="demo" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                            </div>
                                            <button class="btn btn-danger removeFileInput mb-3" id='{{$key}}'>Remove</button>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>

                                    <label for="fileInput1" class="mb-0">Additional Images<span class="text-danger">*</span></label>
                                    <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                        <input id="demo" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    </div>

                                    @error('multipleImages')
                                    <span class="invalid-feedback mt-2" style="display:block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Row -->
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<!-- <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script> -->
<script>
    $(document).ready(function() {
        $('#desc').summernote({
            lang: 'hi',
            fontNames: ['Roboto Light', 'Roboto Regular', 'Roboto Bold', 'Thai Sans Neue Light', 'Thai Sans Neue Regular', 'Thai Sans Neue Bold'],
            fontNamesIgnoreCheck: ['Roboto Light', 'Roboto Regular', 'Roboto Bold', 'Thai Sans Neue Light', 'Thai Sans Neue Regular', 'Thai Sans Neue Bold'],
            fontNamesIgnoreCheck: ['Merriweather']
        });

        // Attach a click event to dynamically added "Remove" buttons
        $("#fileInputContainer").on("click", ".removeFileInput", function(e) {
            var getId = $(this).attr('id');
            e.preventDefault();
            // Check if there's more than one file input field before removing
            if ($("#fileInputContainer .input-group" + getId).length > 0) {
                $(this).closest('.input-group' + getId).remove();
            }
        });
    });
</script>

<script>
    $('input').on('input', function() {
        var inputValue = $(this).val();
        var urlRegex = /^(http|https):\/\/[^\s]*$/i;

        if ((urlRegex.test(inputValue) &&
                inputValue != 'http://' &&
                inputValue != 'url') ||
            (inputValue == '[' ||
                inputValue == ']')
        ) {
            // Display error message
            var fieldId = $(this).attr('name');
            if (fieldId != 'images[]' && fieldId != 'multipleImages[]') {
                $('.' + fieldId).text('Please do not enter any special characters or URLs.');
                valid = false;
                $(".btn").attr('disabled', true);
            }
        } else {
            var fieldId = $(this).attr('name');
            $('.' + fieldId).text('');

            valid = true;
            $(".btn").attr('disabled', false);
        }

        if (inputValue == '') {
            var fieldId = $(this).attr('name');
            if (fieldId != 'images[]' &&
                fieldId != 'multipleImages[]' &&
                fieldId != 'files' &&
                fieldId
            ) {
                $('.' + fieldId).text('This field is required');
                requiredvalid = false;
                $(".btn").attr('disabled', true);
            }
        } else {
            var fieldId = $(this).attr('name');
            $('.' + fieldId).text('');
            requiredvalid = true;
            $(".btn").attr('disabled', false);
        }
    })

    $('textarea').on('input', function() {
        var textareaValue = $(this).val();
        var urlRegex = /^(http|https):\/\/[^\s]*$/i;

        if (urlRegex.test(textareaValue)) {
            // Display error message
            var fieldId = $(this).attr('name');
            $('.' + fieldId).text('Please do not enter special characters or URLs.');
            valid = false;
        }

        if (textareaValue == '') {
            var fieldId = $(this).attr('name');
            if (fieldId) {
                $('.' + fieldId).text('This field is required');

                requiredvalid = false;

                $(".btn").attr('disabled', true);
            }
        } else {
            $(".btn").attr('disabled', false);
        }
    })

    $('#url').on('input', function() {
        var url = $(this).val();
        if (!url.includes('https://www.instamojo.com/EXAM360/')) {
            $('.url').text('Please add instamojo link');
            valid = false;
            $(".btn").attr('disabled', true);
        } else {
            $('.url').text('');
            valid = true;
            $(".btn").attr('disabled', false);
        }
    })
    
    $('#form').submit(function(event) {
        // Reset previous error messages
        $('.error-message').text('');

        // Flag to check if any URL is found
        var valid = true;
        var requiredvalid = true;

        // Iterate over each input field with the class 'no-url-validation'
        $('input').each(function() {
            var inputValue = $(this).val();
            var urlRegex = /^(http|https):\/\/[^\s]*$/i;

            if (urlRegex.test(inputValue) &&
                inputValue != 'http://' &&
                inputValue != 'url'
            ) {
                // Display error message
                var fieldId = $(this).attr('name');
                if (fieldId != 'images[]' && fieldId != 'multipleImages[]') {
                    $('.' + fieldId).text('Please do not enter URLs.');
                    valid = false;
                }
            }

            if (inputValue == '') {
                var fieldId = $(this).attr('name');
                if (fieldId != 'images[]' &&
                    fieldId != 'multipleImages[]' &&
                    fieldId != 'files' &&
                    fieldId
                ) {
                    $('.' + fieldId).text('This field is required');

                    requiredvalid = false;
                }
            }
        });

        $('textarea').each(function() {
            var textareaValue = $(this).val();
            var urlRegex = /^(http|https):\/\/[^\s]*$/i;

            if (urlRegex.test(textareaValue)) {
                // Display error message
                var fieldId = $(this).attr('name');
                $('.' + fieldId).text('Please do not enter URLs.');
                valid = false;
            }

            if (textareaValue == '') {
                var fieldId = $(this).attr('name');
                if (fieldId) {
                    $('.' + fieldId).text('This field is required');

                    requiredvalid = false;
                }
            }
        });

        var url = $('#url').val();

        if (!url.includes('https://www.instamojo.com/EXAM360/')) {
            $('.url').text('Please add instamojo link');
            valid = false;
        } else {
            $('.url').text('');
            valid = true;
        }

        if (!valid || !requiredvalid) {
            event.preventDefault();
        }
    });
</script>
@endpush