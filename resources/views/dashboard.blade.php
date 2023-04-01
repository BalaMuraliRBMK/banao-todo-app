@extends('./layouts.main')
@section('content')
    <br><br><br>
    <div class="md:mx-12">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl username"></h1> <br>
            <label for="my-modal"
                class="cursor-pointer my-3 px-3 py-2 bg-red-600 text-white drop-shadow rounded hover:bg-red-700">Add
                Task</label>
            <input type="checkbox" id="my-modal" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Add Task</h3>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Task</span>
                        </label>
                        <label class="input-group">
                            <span>Task</span>
                            <input type="text" name="task" placeholder="Enter Task Here"
                                class="input input-bordered" />
                        </label>
                    </div>
                    <div class="modal-action">
                        <label onclick="addTask()" for="my-modal"
                            class="cursor-pointer my-3 px-3 py-2 bg-red-600 text-white drop-shadow rounded hover:bg-red-700">ADD</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full ">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if (!localStorage.getItem('banaoToken')) {
                window.location.replace('/login');
            }
            userToken = localStorage.getItem('banaoToken');
            userName = localStorage.getItem('banaoUserName');
            $('.username').html('Hello ' + userName);
            getTasks();
        });

        //Get All Tasks
        function getTasks() {
            var user_id = localStorage.getItem('banaoUserId');
            jQuery.ajax({
                headers: {
                    'Authorization': 'Bearer ' + userToken,
                },
                data: {
                    user_id: user_id,
                },
                url: '/api/todo/all',
                type: 'GET',
                success: function(data) {
                    $('tbody').empty();
                    for (var i = 0; i < data.tasks.length; i++) {
                        $('tbody').append(
                            '<tr>' +
                            '<td>' + data.tasks[i].task + '</td>' +
                            '<td>' + data.tasks[i].status + '</td>' +
                            '<td>' +
                            '<label for="task-modal-' + data.tasks[i].id +
                            '" class="px-3 py-2 bg-red-600 text-white drop-shadow rounded hover:bg-red-700"> Modiify </label>' +
                            '<input type="checkbox" id="task-modal-' + data.tasks[i].id +
                            '" class="modal-toggle" />' +
                            '<div class="modal"> <div class = "modal-box"> <h3 class = "font-bold text-lg" > ' +
                            data.tasks[i].task +
                            '</h3> <br> <div class="form-control"> <label class="label cursor-pointer">' +
                            '<span class="label-text">Pending</span>' +
                            '<input onclick="getStatusFromRadio(this)" data-task-radio-id="' +
                            data.tasks[i].id +
                            '" data-task-radio-status="Pending" value="Pending" type="radio" name="status" class="radio checked:bg-red-500"' +
                            ' /></label></div><div class="form-control"> <label class="label cursor-pointer"> <span class="label-text">Done</span>' +
                            '<input onclick="getStatusFromRadio(this)" data-task-radio-id="' +
                            data.tasks[i].id +
                            '" data-task-radio-status="Done" value = "Done" type = "radio" name = "status" class = "radio checked:bg-green-500" /> </label>' +
                            '</div><div class="modal-action"><label for="task-modal-' +
                            data.tasks[i].id +
                            '"data-task-id="' + data.tasks[i].id + '"' +
                            'data-task-status="' + data.tasks[i].status + '"' +
                            'id="update-task-btn" class="get-status-data-' + data.tasks[i].id +
                            ' update-task-btn px-3 py-2 bg-red-600 text-white drop-shadow rounded hover:bg-red-700" onclick="update(this)"> Update </label> </div> </div> </div> </td>' +
                            '</tr>'
                        );
                    }
                },
                error: function() {
                    // alert("Something went Wrong");
                }
            });
        }

        function getStatusFromRadio(status) {
            var currentStatus = $(status).data('task-radio-status');
            var id = $(status).data('task-radio-id');
            var dt = $('.get-status-data-' + id).data('task-status');
            $('.get-status-data-' + id).data('task-status', currentStatus);
        }


        //Add Task
        function addTask() {
            var user_id = localStorage.getItem('banaoUserId');
            var task = $('input[name="task"]').val();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + userToken,
                },
                data: {
                    user_id: user_id,
                    task: task,
                },
                url: '/api/todo/add',
                type: 'POST',
                success: function(data) {
                    if (data.status == 1) {
                        getTasks();
                        alert(data.message);
                    } else if (data.status == 0) {
                        alert(data.message);
                    } else {
                        alert("Something went Wrong");
                    }
                },
                error: function() {
                    alert("Something went Wrong");
                }
            });
        }

        //Update Task
        function update(task) {
            var id = $(task).data('task-id');
            var status = $(task).data('task-status');
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + userToken,
                },
                data: {
                    id: id,
                    status: status,
                },
                url: '/api/todo/status',
                type: 'PUT',
                success: function(data) {
                    if (data.status == 1) {
                        getTasks();
                        alert(data.message);
                    } else {
                        alert("Something went Wrong");
                    }
                },
                error: function() {
                    alert("Something went Wrong");
                }
            });
        }
    </script>
    <style>
        label:hover {
            cursor: pointer;
        }
    </style>
@endsection
