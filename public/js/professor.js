/* public/js/professor.js — AJAX grade entry */
$(document).ready(function () {

  // ── Step 1: semester selected → load courses ──────────
  $('#semesterSelect').change(function () {
    var semId = $(this).val();
    $('#courseSelect').prop('disabled', true).html('<option value="">Loading…</option>');
    $('#gradeTable, #emptyMsg').addClass('d-none');
    $('#saveBtn').prop('disabled', true);
    $('#feedback').html('');

    if (!semId) return;

    $.get('api/grades.php', { action: 'courses', semester_id: semId }, function (data) {
      var opts = '<option value="">-- Select Course --</option>';
      $.each(data, function (i, c) {
        opts += '<option value="' + c.id + '">' + c.name + ' (' + c.credits + ' cr)</option>';
      });
      $('#courseSelect').html(opts).prop('disabled', false);
    }, 'json').fail(function () {
      $('#courseSelect').html('<option value="">Error loading courses</option>');
    });
  });

  // ── Step 2: course selected → load students ───────────
  $('#courseSelect').change(function () {
    var semId    = $('#semesterSelect').val();
    var courseId = $(this).val();
    $('#gradeTable, #emptyMsg').addClass('d-none');
    $('#saveBtn').prop('disabled', true);
    $('#feedback').html('');

    if (!courseId) return;

    $.get('api/grades.php',
      { action: 'students', semester_id: semId, course_id: courseId },
      function (students) {
        if (!students.length) {
          $('#emptyMsg').removeClass('d-none');
          return;
        }
        var html = '';
        $.each(students, function (i, s) {
          html += '<tr>'
            + '<td class="fw-semibold">' + $('<div>').text(s.name).html() + '</td>'
            + '<td class="text-muted">' + s.id + '</td>'
            + '<td>'
            + '<select name="grade_' + s.id + '" data-student="' + s.id + '" class="form-select form-select-sm grade-input">'
            + buildOptions(s.grade)
            + '</select>'
            + '</td></tr>';
        });
        $('#gradeTable tbody').html(html);
        $('#gradeTable').removeClass('d-none');
        $('#saveBtn').prop('disabled', false);
      }, 'json'
    ).fail(function (xhr) {
      var msg = xhr.status === 403 ? 'You are not assigned to this course.' : 'Error loading students.';
      $('#feedback').html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>' + msg + '</div>');
    });
  });

  // ── Step 3: save grades ───────────────────────────────
  $('#saveBtn').click(function () {
    var semId    = $('#semesterSelect').val();
    var courseId = $('#courseSelect').val();
    var grades   = [];

    $('.grade-input').each(function () {
      grades.push({ student_id: $(this).data('student'), grade: $(this).val() });
    });

    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving…');
    var btn = this;

    $.post('api/grades.php',
      { action: 'save', semester_id: semId, course_id: courseId, grades: grades },
      function (res) {
        var cls = res.success ? 'alert-success' : 'alert-danger';
        var msg = res.success
          ? '<i class="fas fa-check-circle me-2"></i>' + res.saved + ' grade(s) saved successfully.'
          : '<i class="fas fa-times-circle me-2"></i>' + (res.error || 'Error saving grades.');
        $('#feedback').html('<div class="alert ' + cls + '">' + msg + '</div>');
        $(btn).prop('disabled', false).html('<i class="fas fa-floppy-disk me-1"></i>Save Grades');
      }, 'json'
    ).fail(function () {
      $('#feedback').html('<div class="alert alert-danger">Server error. Please try again.</div>');
      $(btn).prop('disabled', false).html('<i class="fas fa-floppy-disk me-1"></i>Save Grades');
    });
  });

  // ── Helper ────────────────────────────────────────────
  function buildOptions(selected) {
    var opts = [['', '-- Grade --'], ['4.0', 'A (4.0)'], ['3.0', 'B (3.0)'],
                ['2.0', 'C (2.0)'], ['1.0', 'D (1.0)'], ['0.0', 'F (0.0)']];
    return opts.map(function (o) {
      var sel = (String(o[0]) === String(selected)) ? ' selected' : '';
      return '<option value="' + o[0] + '"' + sel + '>' + o[1] + '</option>';
    }).join('');
  }

});
