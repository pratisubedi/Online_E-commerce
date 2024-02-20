@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('users.list')}}" class="btn btn-primary">Back</a>
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
            <form  id="userForm" name="userForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Email</label>
                                    <input type="text"  name="email" id="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Password</label>
                                    <input type="text"  name="password" id="password" class="form-control" placeholder="password" value="{{ old('password') }}">
                                    <p class="errors"></p>
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
                                    <label for="phone">Phone</label>
                                    <input type="text"  name="phone" id="phone" class="form-control" placeholder="Enter Valid Phone number" value="{{ old('email') }}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{route('users.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customejs')
<script>
    $('#userForm').submit(function(event){
        event.preventDefault();
        $.ajax({
            url:'{{route("users.store")}}',
            type:'post',
            data:$(this).serializeArray(),
            dataType:'json',
            success:function(response){
                if(response.status==true){
                    window.location.href='{{route("users.list")}}';
                }else{
                    if (response.errors && response.errors['name']) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(response.errors['name']);
                        }
                        if (response.errors && response.errors['email']) {
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(response.errors['email']);
                        }
                        if (response.errors && response.errors['phone']) {
                            $("#phone").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(response.errors['phone']);
                        }
                        if (response.errors && response.errors['password']) {
                            $("#password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(response.errors['password']);
                        }
                }
            },
        });
    });
</script>
@endsection


