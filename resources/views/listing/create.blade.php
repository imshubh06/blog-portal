@extends('layouts.master')

@section('title', __('Create Listing'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('js')

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#desc').summernote();
    });
</script>
@endpush

@section('content')

<!-- CONTAINER -->
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Listing') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Create Listing') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->

    <div class="row">
        <div class="col-md-9 col-xl-9 fields">
            <form action="{{ route('listing.store') }}" method="POST" enctype='multipart/form-data' id='form'>
                @csrf
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Listing') }}
                        </h4>
                    </div> -->

                    <div class="card-body">
                        <div id="progressBar" class="text-end"></div>

                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Title') }}<span class="text-danger">*</span> <span class="text-success">(Prduct Name | Author | Edition | Publication ( Medium ) )</span></label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus placeholder="Title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Description') }}<span class="text-danger">*</span><span class="text-danger"> ( Enter Detail Description without using 3rd party link) </span></div><a href="https://chat.openai.com">ChatGPT</a>
                                </label>
                                <!-- <div id="summernote" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
                                    {{ old('description') }}
                                </div> -->

                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" autofocus placeholder="Description" rows="10">{{ old('description') }}</textarea>
                                <span class="error-message description" style="color:red;"></span>

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
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">
                                <span class="error-message mrp" style="color:red;"></span>

                                @error('mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="selling_price" class="form-label">{{ __('Selling Price') }}<span class="text-danger">*</span></label>
                                <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') }}" autocomplete="selling_price" autofocus placeholder="Selling Price">
                                <span class="error-message selling_price" style="color:red;"></span>

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
                                <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') }}" autocomplete="publication" autofocus placeholder="Publisher">
                                <span class="error-message publication" style="color:red;"></span>

                                @error('publication')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="author_name" class="form-label">{{ __('Author Name') }}<span class="text-danger">*</span></label>
                                <input id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') }}" autocomplete="author_name" autofocus placeholder="Author name">
                                <span class="error-message author_name" style="color:red;"></span>

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') }}" autocomplete="edition" autofocus placeholder="Edition">
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
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple name="label[]">
                                @foreach($categories as $category)
                                <option value="{{ $category['term'] }}" {{ $category['term'] == 'Product' ? 'selected' : '' }}>
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
                                <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}" autocomplete="sku" autofocus placeholder="SKU">
                                <span class="error-message sku" style="color:red;"></span>

                                @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') }}" autocomplete="language" autofocus placeholder="Language">
                                <span class="error-message language" style="color:red;"></span>

                                @error('language')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                <input id="pages" type="text" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') }}" autocomplete="pages" autofocus placeholder="No. of Pages">
                                <span class="error-message pages" style="color:red;"></span>

                                @error('pages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="condition" class="form-label">{{ __('Condition') }}<span class="text-danger">*</span></label>
                                <select class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}">
                                    <option value="">--Select--</option>
                                    <option value="New">New</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Old">Old</option>
                                </select>
                                <!-- <input id="condition" type="text" class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}" autocomplete="condition" autofocus placeholder="Binding Type"> -->
                                <span class="error-message condition" style="color:red;"></span>

                                @error('condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="binding" class="form-label">{{ __('Binding Type') }}<span class="text-danger">*</span></label>
                                <select class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}">
                                    <option value="">--Select--</option>
                                    <option value="Hardcover">Hardcover</option>
                                    <option value="Paperback">Paperback</option>
                                </select>
                                <!-- <input id="binding" type="text" class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}" autocomplete="binding" autofocus placeholder="Condition"> -->
                                <span class="error-message binding" style="color:red;"></span>
                                @error('binding')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Insta Mojo URL') }}</label>
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" autocomplete="url" autofocus placeholder="Insta Mojo url">
                                <span class="error-message url" style="color:red;"></span>

                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="addUrls">
                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Main Image URL') }}</label>
                                <input id="url" type="text" class="form-control @error('images') is-invalid @enderror" name="images[]" value="" autocomplete="images" autofocus placeholder="Base URL">
                                <span class="error-message images" style="color:red;"></span>

                                @error('images')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button type='button' id='addFileInput' class="btn btn-primary">Add More Images</button>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-warning float-right" id='draft'>Save as Draft</button>
                            <button type="submit" class="btn btn-success float-right">Publish</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div id="fileInputContainer">
                            <div class="form-group">
                                <!-- <label for="fileInput1">Main Images<span class="text-danger">*</span>( Only 1 Image )</label>
                                <div class="form-group mb-0" @error('images') style="border: red 2px dotted;" @enderror>
                                    <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="fileInput1" name="images[]" /> -->
                                <!-- <form action="{{ route('convert.image') }}" method="post" enctype="multipart/form-data" id='singleImageForm'>
                                        @csrf
                                        <input id="fileInput1" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" accept="jpg">
                                        <div id='singleImageDownload' style="display: none;">
                                            <a href='#' class="w-100 d-flex justify-content-end my-4" id='downloadImage'>
                                                <img src="/downlod-icon.png" />
                                            </a>
                                        </div>
                                    </form> -->
                            </div>

                            <label for="fileInput1" class="mt-2">Images<span class="text-danger">*</span>( Multiple Images )</label>
                            <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                <form action="{{ route('convert.image') }}" method="post" enctype="multipart/form-data" id='multipleImagesform'>
                                    @csrf
                                    <input id="multipleFiles" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    <div id='multiImagesDownload' style="display: none;">
                                        <a href='#' class="w-100 d-flex justify-content-end my-4" id='downloadMultipleImage'>
                                            <img src="/downlod-icon.png" />
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
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

@include('listing.script')

<script>
    $(document).ready(function() {
        $('#fileInput1').change(function() {
            $("#singleImageDownload").show();
        })

        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        })

        $('#downloadImage').click(function() {
            $('#singleImageForm').submit();
        })

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        })

        // $("#draft").click(function(e) {
        //     e.preventDefault();
        //     $("#form").append("<input type='hidden' name='isDraft' value=1 />");

        //     $("#form").submit();
        // });

        // $('input').on('input', function() {
        //     var inputValue = $(this).val();
        //     var urlRegex = /^(http|https):\/\/[^\s]*$/i;

        //     if ((urlRegex.test(inputValue) &&
        //             inputValue != 'http://' &&
        //             inputValue != 'url') ||
        //         (inputValue.includes('[') ||
        //             inputValue.includes(']'))
        //     ) {
        //         // Display error message
        //         var fieldId = $(this).attr('name');
        //         if (fieldId != 'images[]' && fieldId != 'multipleImages[]') {
        //             $(this).css('border', '1px red solid');
        //             $('.' + fieldId).text('Please do not enter any special characters or URLs.');
        //             valid = false;
        //             $(".fields .btn").attr('disabled', true);
        //         }
        //     } else {
        //         var fieldId = $(this).attr('name');
        //         $(this).css('border', '1px solid #e9edf4');

        //         $('.' + fieldId).text('');
        //         valid = true;
        //         $(".fields .btn").attr('disabled', false);
        //     }

        //     if (inputValue == '') {
        //         var fieldId = $(this).attr('name');
        //         if (fieldId != 'images[]' &&
        //             fieldId != 'multipleImages[]' &&
        //             fieldId != 'files' &&
        //             fieldId
        //         ) {
        //             $(this).css('border', '1px red solid');
        //             $('.' + fieldId).text('This field is required');
        //             requiredvalid = false;
        //             $(".fields .btn").attr('disabled', true);
        //         }
        //     } else {
        //         var fieldId = $(this).attr('name');
        //         $(this).css('border', '1px solid #e9edf4');

        //         // $('.' + fieldId).text('');
        //         requiredvalid = true;
        //         $(".fields .btn").attr('disabled', false);
        //     }
        // })

        // $('textarea').on('input', function() {
        //     var textareaValue = $(this).val();
        //     var urlRegex = /^(http|https):\/\/[^\s]*$/i;

        //     if (urlRegex.test(textareaValue)) {
        //         $(this).css('border', '1px red solid');
        //         // Display error message
        //         var fieldId = $(this).attr('name');
        //         $('.' + fieldId).text('Please do not enter special characters or URLs.');
        //         valid = false;
        //     }

        //     if (textareaValue == '') {
        //         var fieldId = $(this).attr('name');
        //         if (fieldId) {
        //             $(this).css('border', '1px red solid');
        //             $('.' + fieldId).text('This field is required');
        //             requiredvalid = false;

        //             $(".btn").attr('disabled', true);
        //         }
        //     } else {
        //         $(this).css('border', '1px solid #e9edf4');

        //         $(".btn").attr('disabled', false);
        //     }
        // })

        // $('#url').on('input', function() {
        //     var url = $(this).val();
        //     if (!url.includes('https://www.instamojo.com/EXAM360/')) {
        //         $(this).css('border', '1px red solid');

        //         $('.url').text('Please add instamojo link');
        //         valid = false;
        //         $(".btn").attr('disabled', true);
        //     } else {
        //         $('.url').text('');
        //         $(this).css('border', '1px solid #e9edf4');
        //         valid = true;
        //         $(".btn").attr('disabled', false);
        //     }
        // })

        // $('#form').submit(function(event) {
        //     // Reset previous error messages
        //     $('.error-message').text('');

        //     // Flag to check if any URL is found
        //     var valid = true;
        //     var requiredvalid = true;

        //     // Iterate over each input field with the class 'no-url-validation'
        //     $('input').each(function() {
        //         var inputValue = $(this).val();
        //         var urlRegex = /^(http|https):\/\/[^\s\[\]]*$/i;

        //         if ((urlRegex.test(inputValue) &&
        //                 inputValue != 'http://' &&
        //                 inputValue != 'url') ||
        //             (inputValue.includes('[') ||
        //                 inputValue.includes(']'))
        //         ) {
        //             // Display error message
        //             var fieldId = $(this).attr('name');
        //             if (fieldId != 'images[]' && fieldId != 'multipleImages[]') {
        //                 $('.' + fieldId).text('Please do not enter URLs.');
        //                 valid = false;
        //             }
        //         }

        //         if (inputValue == '') {
        //             var fieldId = $(this).attr('name');
        //             if (fieldId != 'images[]' &&
        //                 fieldId != 'multipleImages[]' &&
        //                 fieldId != 'files' &&
        //                 fieldId
        //             ) {
        //                 $('.' + fieldId).text('This field is required');

        //                 requiredvalid = false;
        //             }
        //         }
        //     });

        //     $('textarea').each(function() {
        //         var textareaValue = $(this).val();
        //         var urlRegex = /^(http|https):\/\/[^\s]*$/i;

        //         if (urlRegex.test(textareaValue)) {
        //             // Display error message
        //             var fieldId = $(this).attr('name');
        //             $('.' + fieldId).text('Please do not enter URLs.');
        //             valid = false;
        //         }

        //         if (textareaValue == '') {
        //             var fieldId = $(this).attr('name');
        //             if (fieldId) {
        //                 $('.' + fieldId).text('This field is required');

        //                 requiredvalid = false;
        //             }
        //         }
        //     });

        //     $('select').each(function() {
        //         var textareaValue = $(this).val();

        //         if (textareaValue == '') {
        //             var fieldId = $(this).attr('name');
        //             if (fieldId) {
        //                 $('.' + fieldId).text('This field is required');

        //                 requiredvalid = false;
        //             }
        //         }
        //     });

        //     var url = $('#url').val();
        //     if (!url.includes('https://www.instamojo.com/EXAM360/')) {
        //         $('.url').text('Please add instamojo link');
        //         valid = false;
        //     } else {
        //         $('.url').text('');
        //         valid = true;
        //     }

        //     // Prevent form submission if a URL is found
        //     if (!valid || !requiredvalid) {
        //         event.preventDefault();
        //     }
        // });

        // setTimeout(function() {
        //     calculateFields();
        // }, 1000);

        // $(".fields select.form-control").on('change', function() {
        //     calculateFields();
        // });

        // $("input").on('input', function() {
        //     calculateFields();
        // })

        // function calculateFields() {
        //     var totalFields = $(".fields input.form-control").length + $(".fields select.form-control").length;
        //     var filledFields = 0;
        //     var emptyFields = 0;
        //     var notDefined = 0;

        //     $(".fields input.form-control").each(function(index) {
        //         var fieldValue = $(this).val();

        //         if (fieldValue != '' && fieldValue != 'http://') {
        //             filledFields++;
        //         } else if ($(this).attr('name') !== undefined) {
        //             emptyFields++;
        //         } else if ($(this).attr('name') === undefined) {
        //             notDefined++;
        //         }
        //     });

        //     $(".fields select.form-control").each(function() {
        //         var fieldValue = $(this).val();
        //         if (fieldValue == '') emptyFields++;
        //     });

        //     $("#progressBar").html(emptyFields + " Remaining Out of " + (totalFields - notDefined) + " Fields");
        // }

        // $('#download').click(function(e) {
        //     console.log($("#formImage").serialize());
        //     e.preventDefault();
        //     // $("#formImage").submit();
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('process.image') }}",
        //         // contentType: false,
        //         // processData: false,
        //         data: {
        //             otp: 'asdas ',
        //         },

        //         headers: {
        //             'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        //         },

        //         success: function(result) {
        //             console.log(result);
        //         },
        //         error: function(error) {
        //             console.log(error.responseText);
        //         }
        //     });
        // })
    })
</script>
@endpush