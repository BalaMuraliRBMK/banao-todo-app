<div class="w-full bg-white fixed top-0 py-4 drop-shadow flex justify-between">
    <div class="logo ml-6">
        <h1>TODO - BANAO</h1>
    </div>
    <div class="links mr-6">

    </div>
</div>
<script>
    $(document).ready(function() {
        if (localStorage.getItem('banaoToken')) {
            $('.links').html(
                '<a href="/dashboard" class="mx-2 text-gray-700">Dashboard</a>' +
                '<button id="logout-btn">logout</button>'
            );
        } else {
            $('.links').html(
                '<a href="/login" class="mx-2 text-gray-700">Login</a>' +
                '<a href="/register" class="mx-2 text-gray-700">Register</a>'
            );
        }
        $("#logout-btn").click(function() {
            userToken = localStorage.getItem('banaoToken');
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + userToken,
                },
                url: '/api/logout',
                type: 'POST',
                success: function(data) {
                    localStorage.removeItem('banaoToken');
                    localStorage.removeItem('banaoUserId');
                    localStorage.removeItem('banaoUserName');
                    window.location.replace("/login");
                },
                error: function(data, xhr) {

                }
            });
        });
    });
</script>
