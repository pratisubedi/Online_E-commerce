@extends('Front.layout.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('Front.Account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    @include('admin.message')
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <form name="profileUpdate" id="profileUpdate">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input value="{{$profileInformations->name}}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                    <p class="errors"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{$profileInformations->email}}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                    <p class="errors"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input value="{{$profileInformations->phone}}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                    <p class="errors"></p>
                                </div>

                                <div class="mb-3">
                                    <label for="phone">Address</label>
                                    <textarea name="address" id="address" class="form-control" cols="30" rows="5" placeholder="Enter Your Address"></textarea>
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script>
        $('#profileUpdate').submit(function(event){
            event.preventDefault();
            $.ajax({
                url:'{{route("account.profileupdate")}}',
                type:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(response){
                    if(response.status==true){
                        window.location.href = '{{ route("account.profile") }}';
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
                    }
                }
            });
        });
    </script>
@endsection

