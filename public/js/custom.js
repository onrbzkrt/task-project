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
  
  
  