@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    @include('/admin.message')
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('categories.store') }}" id="categoryForm" name="categoryForm" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                                    <p></p>
                                    @error('name')
                                        <div class="d-block text-danger invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text"  name="slug" id="slug" readonly class="form-control" placeholder="Slug" value="{{ old('slug') }}">
                                    <p></p>
                                    @error('slug')
                                        <div class="d-block text-danger invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image" id="image">Image</label>
                                    <div class="dropzone dz-clickable" id="image">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload. <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" value="{{ old('status') }}">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Show on Home</label>
                                    <select name="showHome" id="showHome" class="form-control" value="{{ old('showHome') }}">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>									
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customejs')

<script>
Dropzone.autoDiscover = false;
    $(document).ready(function() {
    const dropzone = new Dropzone("#image", {
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url: "{{ route('temp-image.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFile: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $("#image_id").val(response.image_id);
            console.log(response);
        }
    });
        // $("#categoryForm").submit(function(event) {
        //     event.preventDefault();
        //     var element = $(this);
        //     $.ajax({
        //         url: '{{ route("categories.store") }}', // Update URL if needed
        //         type: 'post',
        //         data: element.serializeArray(),
        //         dataType: 'json',
        //         success: function(response) {
        //             var errors = response['errors'];
        //             if (errors['name']) {
        //                 $("#name").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback').html(errors['name']);
        //             } else {
        //                 $("#name").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback').html('');
        //             }
        //             if (errors['slug']) {
        //                 $("#slug").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback').html(errors['slug']);
        //             } else {
        //                 $("#slug").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback').html('');
        //             }
        //         },
        //         error: function(jqXHR, exception) {
        //             console.log("Something went wrong");
        //         }
        //     });
        // });
       $('#name').change(function(){
            element=$(this);
            $.ajax({
                url:'{{route("getSlug")}}',
                type:'get',
                data:{title: element.val()},
                dataType:'json',
                success:function(response){
                    if(response["status"]==true){
                        $("#slug").val(response["slug"]);
                    }
                }
            });
       });

    
    });
</script>
@endsection
    

