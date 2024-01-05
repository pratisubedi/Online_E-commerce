@extends('Front.layout.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="/examples/actions/confirmation.php" method="post" name="registrationForm" id="registrationForm">
                @csrf
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="cpassword" name="password_confirmation">
                    <p></p>
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>
            <div class="text-center small">Already have an account? <a href="{{route('account.login')}}">Login Now</a></div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script type="text/javascript">
    $('#registrationForm').submit(function(event){
    event.preventDefault();
    $("button[type='submit']").prop('disabled',true);
    $.ajax({
        url: '{{ route("account.processRegister") }}',
        type: 'post',
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);

            if (response && response.hasOwnProperty('status')) {
                if (response.status === true) {
                    var success = response.success;
                    console.log('Registration successful:', success);
                    // Redirect or perform other actions on success
                     window.location.href = "{{ route('account.login') }}";
                }

                var errors = response['errors'];
                if (errors) {
                    handleFormErrors(errors);
                }
            } else {
                console.error('Unexpected response structure:', response);
            }
        },
        error: function(jqXHR, exception) {
            console.error("AJAX Error: ", jqXHR, exception);
        }
    });
});

function handleFormErrors(errors) {
    if (errors['name']) {
        $("#name").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['name']);
    } else {
        $("#name").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }

    if (errors['email']) {
        $("#email").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['email']);
    } else {
        $("#email").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }

    if (errors['password']) {
        $("#password").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['password']);
    } else {
        $("#password").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
}

        // $('#registrationForm').submit(function(event){
        //     event.preventDefault();
        //     $.ajax({
        //         url:'{{route("account.processRegister")}}',
        //         type:'post',
        //         data:$(this).serializeArray(),
        //         dataType:'json',
        //         success:function(response){
        //             if(response["status"]==true){
        //                 var success=response.success;
        //                // window.location.href="{{route('brands.index')}}"
        //                 //window.location.href="{{route('categories.index')}}";
        //                 console.log(success);
        //             }
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
        //             if (errors['email']) {
        //                 $("#email").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback').html(errors['email']);
        //             } else {
        //                 $("#email").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback').html('');
        //             }
        //             if (errors['password']) {
        //                 $("#password").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback').html(errors['password']);
        //             } else {
        //                 $("#password").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback').html('');
        //             }
        //         },
        //         error:function(JQXHR,execption){
        //             console.log("AJAX Error: ", JQXHR, exception);
        //         }
        //     });
        // });
    </script>
@endsection
