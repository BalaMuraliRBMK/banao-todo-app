@extends('./layouts.main')
@section('content')
    <div class="xs:w-full h-screen flex justify-center items-center">
        <div class="w-full max-w-sm">
            <form class="bg-white shadow-md rounded px-8 pt-8 pb-8 mb-4">
                {{-- @csrf --}}
                <h1 class="font-bold text-2xl text-gray-800">Sign Up</h1>
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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input type="text" name="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" type="text" placeholder="Username" required>
                    <p class="text-red-500 text-xs italic" id="name-error"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input type="password" name="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="password" type="text" placeholder="Password" required>
                    <p class="text-red-500 text-xs italic" id="password-error"></p>
                </div>
                {{-- <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input
                        class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        id="password" type="password" placeholder="******************">
                    <p class="text-red-500 text-xs italic">Please choose a password.</p>
                </div> --}}
                <div class="flex items-center justify-between  mt-6">
                    <button
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button" id="register-btn">
                        Sign Up
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
            $("#register-btn").click(function() {
                $("#register-btn").html('<i class="fas fa-circle-notch animate-spin"></i> Creating Acc..');
                $('#name-error').hide();
                $('#email-error').hide();
                $('#password-error').hide();
                $("input[name=name]").removeClass("border-red-500");
                $("input[name=email]").removeClass("border-red-500");
                $("input[name=password]").removeClass("border-red-500");
                var name = $("input[name=name]").val();
                var email = $("input[name=email]").val();
                var password = $("input[name=password]").val();
                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/register',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                    },
                    success: function(data) {
                        if (data.code === 422) {
                            $("#register-btn").html('Sign Up');
                            if (data.errors.name != undefined) {
                                $("input[name=name]").addClass("border-red-500");
                                $('#name-error').show();
                                $('#name-error').html(data.errors.name);
                            }
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
                        }
                        if (data.code === 200) {
                            $("#register-btn").html('Account Created');
                            localStorage.setItem('banaoToken', data.token);
                            localStorage.setItem('banaoUserId', data.user_id);
                            localStorage.setItem('banaoUserName', data.username);
                            window.location.replace("/dashboard");
                        }
                    },
                    error: function(data, xhr) {
                        $("#register-btn").html('Sign Up');
                    }
                });
            });
        });
    </script>
@endsection
