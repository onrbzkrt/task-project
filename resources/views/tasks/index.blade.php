<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<title>Task List</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
	<div class="container">
		<h1>Task List</h1>
        <button id="addTaskBtn" class="btn btn-primary" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
            <br>
            <table id="tasksTable">
  <thead>
    <tr>
      <th>Priority</th>
      <th>Task Name</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tasks as $task)
    <tr data-id="{{ $task->id }}">
      <td id='priorityUpt'>{{ $task->priority }}</td>
      <td>{{ $task->name }}</td>
      <td>{{ $task->created_at }}</td>
      <td>{{ $task->updated_at }}</td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn btn-sm btn-primary edit-task" data-name="{{ $task->name}}" data-id="{{ $task->id }}">Edit</button>
          <form action="{{ route('tasks.destroy', ['task' => $task->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


<div id='links'>

    {{ $tasks->links() }}

</div>



            

<!-- Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addTaskForm">
        <meta name="csrf-token" content="{{ csrf_token() }}">

          <div class="mb-3">
            <label for="taskName" class="form-label">Task Name</label>
            <input type="text" class="form-control" id="taskName" name="taskName">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addTaskSubmitBtn">Add Task</button>
      </div>
    </div>
  </div>
</div>


<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">
            <form id="editTaskForm" action="{{ route('tasks.update', ['task' => !empty($task->id) ? $task->id : 0]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="editTaskName" class="form-label">Task Name</label>
                <input type="text" class="form-control" id="editTaskName" name="name">
            </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="editTaskForm" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>




    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js" integrity="sha512-EKWWs1ZcA2ZY9lbLISPz8aGR2+L7JVYqBAYTq5AXgBkSjRSuQEGqWx8R1zAX16KdXPaCjOCaKE8MCpU0wcHlHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

$(document).ready(function() {
    // Add Task button click event
    $('#addTaskBtn').click(function() {
      $('#addTaskModal').modal('show');
    });
  
    // Add Task form submit event
    $('#addTaskSubmitBtn').click(function() {
      var taskName = $('#taskName').val();
  
      // Send Ajax request to store task
      $.ajax({
        url: '/tasks',
        method: 'POST',
        data: {
          taskName: taskName,
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        success: function(response) {
          // Hide modal
          $('#addTaskModal').modal('hide');
  
          // Clear input field
          $('#taskName').val('');
  
          // Reload tasks table
          $('#tasksTable').load(location.href + ' #tasksTable');
          $('#links').load(location.href + ' #links');
          setTimeout(function() {
              sortFunc();
          }, 200);
  
  
  
        }
      });
    });
  });
  
  $(document).on('click', '.edit-task', function() {
    var taskId = $(this).data('id');
    var taskName = $(this).data('name')
    var url = "{{ route('tasks.update', ['task' => ':taskId']) }}".replace(':taskId', taskId);
    $('#editTaskForm').attr('action', url);
    $('#editTaskName').val(taskName);
    $('#editTaskModal').modal('show');
  });
  
  $(document).on('submit', '#editTaskForm', function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var data = form.serialize();
    $.ajax({
      url: url,
      method: 'PUT',
      data: data,
      success: function(response) {
        // Başarılı olduğunda yapılacak işlemler
        $('#editTaskModal').modal('hide');
        $('#tasksTable').load(location.href + ' #tasksTable');
        setTimeout(function() {
              sortFunc();
          }, 200);
      },
      error: function(response) {
        // Hata olduğunda yapılacak işlemler
      }
    });
  });

  sortFunc();

  function sortFunc() {
      var page = getParameterByName('page');
  
      $("#tasksTable tbody").sortable({
          axis: "y",
          containment: "parent",
          update: function(event, ui) {
              var data = $(this).sortable("toArray",  {attribute: "data-id"});
              $.ajax({
                  data: {task : data, page: page},
                  type: "POST",
                  url: "{{ route('tasks.reorder') }}",
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function() {
                      $('#tasksTable').load(location.href + ' #tasksTable');
                      //I used delay because of asynchronous problem.
                      setTimeout(function() {
                          sortFunc();
                      }, 200);
  
                  },
                  error: function(xhr, status, error) {
                      console.log(xhr.responseText);
                  }
              });
          }
      });
      $("#tasksTable tbody").disableSelection();
  }
  
  
  function getParameterByName(name) {
      var url = window.location.href;
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
</script>
</html>
