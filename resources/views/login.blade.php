@extends('./layouts.main')
@section('content')
    <div class="xs:w-full h-screen flex justify-center items-center">
        <div class="w-full max-w-sm">
            <form class="bg-white shadow-md rounded px-8 pt-8 pb-8 mb-4">
                {{-- @csrf --}}
                <h1 class="font-bold text-2xl text-gray-800">Sign In</h1>
                <p class="text-gray-800">Existing User ? <a href="/login" class="text-indigo-600">Click here</a></p>
                <br>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input type="email" name="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" type="text" placeholder="Email" required>
                    <p class="text-red-500 text-xs italic" id="email-error"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input type="password" name="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="password" type="text" placeholder="Password" required>
                    <p class="text-red-500 text-xs italic" id="password-error"></p>
                    <p class="text-red-500 text-xs italic" id="password-mismatch-error"></p>
                </div>
                <div class="flex items-center justify-between  mt-6">
                    <button
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button" id="login-btn">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        body,
        input {
            background: #F1F5F9;
        }
    </style>
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('banaoToken')) {
                window.location.replace('/dashboard');
            }
            $("#login-btn").click(function() {
                $("#login-btn").html('<i class="fas fa-circle-notch animate-spin"></i> Authenticating...');
                $('#email-error').hide();
                $('#password-error').hide();
                $('#password-mismatch-error').hide();
                $("input[name=email]").removeClass("border-red-500");
                $("input[name=password]").removeClass("border-red-500");
                var email = $("input[name=email]").val();
                var password = $("input[name=password]").val();
                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/login',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                    },
                    success: function(data) {
                        if (data.code === 422) {
                            $("#login-btn").html('Sign In');
                            if (data.errors.email != undefined) {
                                $("input[name=email]").addClass("border-red-500");
                                $('#email-error').show();
                                $('#email-error').html(data.errors.email);
                            }
                            if (data.errors.password != undefined) {
                                $("input[name=password]").addClass("border-red-500");
                                $('#password-error').show();
                                $('#password-error').html(data.errors.password);
                            }
                        } else if (data.code === 401) {
                            $("#login-btn").html('Sign In');
                            $('#password-mismatch-error').show();
                            $('#password-mismatch-error').html("Invalid Password or Email");
                        } else if (data.code === 200) {
                            $("#login-btn").html('Success');
                            localStorage.setItem('banaoToken', data.token);
                            localStorage.setItem('banaoUserId', data.user_id);
                            localStorage.setItem('banaoUserName', data.username);
                            window.location.replace("/dashboard");
                        }
                    },
                    error: function(data, xhr) {
                        $("#login-btn").html('Sign In');
                    }
                });
            });
        });
    </script>
@endsection
