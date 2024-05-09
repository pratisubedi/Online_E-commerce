@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Static Pages</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('staticPage.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="updatePages" id="updatePages">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$page->name}}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$page->slug}}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="summernote" cols="30" rows="10">{{$page->content}}</textarea>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('staticPage.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection
@section('customejs')
<script>

    $("#updatePages").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $.ajax({
                url: '{{ route("staticPage.update",$page->id) }}', // Update URL if needed
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                success: function(response) {
                    var errors = response['errors'];
                    if(response["status"]==true){
                        window.location.href="{{route('staticPage.index')}}"
                    }
                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html('');
                    }
                    if (errors['slug']) {
                        $("#slug").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['slug']);
                    } else {
                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html('');
                    }
                    if (errors['content']) {
                        $("#content").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['content']);
                    } else {
                        $("#content").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html('');
                    }
                },
                error: function(jqXHR, exception) {
                        console.log("AJAX Error: ", jqXHR, exception);
                    }

            });
        });
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

</script>
@endsection

