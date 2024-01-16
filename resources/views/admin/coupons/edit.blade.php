@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="color: green;"><b>Edit Coupons Code No: {{$coupons->id}}</b></h1>
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
            {{-- <form action="{{ route('coupons.update',$coupons->id) }}"  method="POST"> --}}
                <form id="couponsForm" name="couponsForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Code</label>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Enter Coupons Code" value="{{$coupons->code}}">
                                    <p ></p>
                                    {{-- @error('name')
                                        <div class="d-block text-danger invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text"  name="name" id="name"  class="form-control" placeholder="name" value="{{$coupons->name}}">
                                    <p class="errors"></p>
                                    @error('name')
                                        <div class="d-block text-danger invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" id="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="10" >{{$coupons->description}}</textarea>
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" value="{{ old('status') }}">
                                        <option {{($coupons->status==1)? 'selected':''}} value="1">Active</option>
                                        <option {{($coupons->status==0)? 'selected':''}}    value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses" id="max_uses">Max_Uses</label>
                                    <input type="text"  name="max_uses" id="max_uses"  class="form-control" placeholder="max_uses" value="{{$coupons->max_uses}}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses_user" id="max_uses_user">Maximum uses user</label>
                                    <input type="text"  name="max_uses_user" id="max_uses_user"  class="form-control" placeholder="max_uses" value="{{$coupons->max_uses_user}}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" id="type">Type</label>
                                    <select name="type" id="type" class="form-control" >
                                        <option {{($coupons->type=='percent')?'selected':''}} value="percent">Percent</option>
                                        <option {{($coupons->type=='fixed')?'selected':''}} value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount" id="discount_amount">Discount Amount</label>
                                    <input type="text"  name="discount_amount" id="discount_amount"  class="form-control" placeholder="Discount amount" value="{{ $coupons->discount_amount }}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount" id="min_amount">Minimum Amount</label>
                                    <input type="text"  name="min_amount" id="min_amount"  class="form-control" placeholder="Minimum amount" value="{{ $coupons->min_amount }}">
                                    <p  class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_at" >Start Date</label>
                                    <input autocomplete="off" type="text"  name="start_at" id="start_at"  class="form-control" placeholder="Enter start date" value="{{ $coupons->starts_at }}" >
                                    <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expire_at">End Date </label>
                                    <input autocomplete="off" type="text"  name="expire_at" id="expire_at"  class="form-control" value="{{ $coupons->expire_at }}">
                                    <p class="errors"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('coupons.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customejs')

<script>
    $(document).ready(function(){
        $('#start_at').datetimepicker({
            format:'Y-m-d H:i:s',
        });
        $('#expire_at').datetimepicker({
            format:'Y-m-d H:i:s',
        });
    });
    $('#couponsForm').submit(function(event){
        event.preventDefault();
        $.ajax({
            url:'{{route("coupons.update",$coupons->id)}}',
            type:'post',
            data:$(this).serializeArray(),
            dataType:'json',
             success: function(response) {
                if (response.status == true) {
                    window.location.href = '{{ route("coupons.index") }}';
                } else {
                    // Check if 'errors' is present in the response
                    if (response.errors && response.errors['code']) {
                        $("#code").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['code']);
                    }
                    if (response.errors && response.errors['name']) {
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['name']);
                    }
                    if (response.errors && response.errors['description']) {
                        $("#description").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['description']);
                    }
                    if (response.errors && response.errors['max_uses']) {
                        $("#max_uses").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['max_uses']);
                    }
                    if (response.errors && response.errors['max_uses_user']) {
                        $("#max_uses_user").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['max_uses_user']);
                    }
                    if (response.errors && response.errors['discount_amount']) {
                        $("#discount_amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['discount_amount']);
                    }
                    if (response.errors && response.errors['min_amount']) {
                        $("#min_amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['min_amount']);
                    }
                    if (response.errors && response.errors['start_at']) {
                        $("#start_at").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['start_at']);
                    }
                    if (response.errors && response.errors['expire_at']) {
                        $("#expire_at").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(response.errors['expire_at']);
                    }

                }
            },

        });
    });
</script>
@endsection


