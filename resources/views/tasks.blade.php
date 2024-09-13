<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="text-center mt-3">To Do List</h2>
            <div class="mt-3 text-center">
                <input type="text" id="new-task" placeholder="Enter new task">
                <button id="add-task" class="btn btn-primary">Add Task</button>
                <button id="show-all-tasks" class="btn btn-warning text-light">Show All Tasks</button>
                <div id="errors" class="text-danger mt-3 mb-3" style="height: 50px;"></div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Task</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tasks-list"></tbody>
            </table>
        </div>
    </div>

    <template id="task-template">
        <tr class="task-item">
            <th scope="row" class="s-no"></th>
            <td class="task-name"></td>
            <td class="task-status">Incomplete</td>
            <td class="task-actions d-flex">
                <input type="checkbox" class="task-checkbox">
                <button class="delete-task btn btn-danger">Delete</button>
            </td>
        </tr>
    </template>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="{{ asset('index.js') }}"></script>
</html>
