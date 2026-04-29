/* student.js */
$(document).ready(function () {

  var urlParams = new URLSearchParams(window.location.search);
  var currentPage = urlParams.get('page') || '';

  if (currentPage === 'student.dashboard') loadDashboard();
  else if (currentPage === 'student.history') loadHistory();

  function loadDashboard() {
    $.get('api/gpa.php', { action: 'current' }, function (data) {
      $('#loadingSpinner').addClass('d-none');

      if (data.error) {
        $('#errorMsg').text(data.error);
        $('#errorContainer').removeClass('d-none');
        return;
      }

      $('#semTitle').text(data.semester.label + ' — ' + data.semester.academic_year);

      var html = '';
      $.each(data.courses, function (i, c) {
        var grade = c.grade !== null ? gradeToLetter(c.grade) + ' (' + c.grade + ')' : 'Pending';
        html += '<tr><td>' + $('<div>').text(c.name).html() + '</td>'
          + '<td>' + c.credits + '</td>'
          + '<td>' + grade + '</td>'
          + '<td>' + (c.grade !== null ? (c.grade * c.credits).toFixed(1) : '—') + '</td></tr>';
      });
      $('#coursesTable').html(html);

      var gpa = data.gpa !== null ? parseFloat(data.gpa).toFixed(2) : 'N/A';
      $('#gpaValue').text(gpa);

      var info = gpaInfo(data.gpa);
      $('#gpaBadge').removeClass().addClass('badge fs-6 bg-' + info.color).text(info.label);

      $('#gradesContainer').removeClass('d-none');
    }, 'json').fail(function () {
      $('#loadingSpinner').addClass('d-none');
      $('#errorMsg').text('Failed to load grades.');
      $('#errorContainer').removeClass('d-none');
    });
  }

  function loadHistory() {
    $.get('api/gpa.php', { action: 'history' }, function (semesters) {
      $('#historyLoading').addClass('d-none');

      if (!semesters.length) {
        $('#historyErrorMsg').text('No enrollment history found.');
        $('#historyError').removeClass('d-none');
        return;
      }

      var acc = '';
      $.each(semesters, function (i, sem) {
        var info = gpaInfo(sem.gpa);
        var gpaText = sem.gpa !== null ? parseFloat(sem.gpa).toFixed(2) : 'N/A';

        acc += '<div class="accordion-item mb-2">'
          + '<h2 class="accordion-header">'
          + '<button class="accordion-button ' + (i > 0 ? 'collapsed' : '') + '" type="button" data-bs-toggle="collapse" data-bs-target="#acc' + i + '">'
          + sem.label + ' — ' + sem.academic_year
          + ' <span class="badge bg-' + info.color + ' ms-2">' + gpaText + '</span>'
          + '</button></h2>'
          + '<div id="acc' + i + '" class="accordion-collapse collapse ' + (i === 0 ? 'show' : '') + '">'
          + '<div class="accordion-body p-0">'
          + '<table class="table table-sm mb-0"><thead class="table-light"><tr><th>Course</th><th>Credits</th><th>Grade</th><th>Points</th></tr></thead><tbody>';

        $.each(sem.courses, function (j, c) {
          var gl = c.grade !== null ? gradeToLetter(c.grade) + ' (' + c.grade + ')' : 'Pending';
          acc += '<tr><td>' + $('<div>').text(c.name).html() + '</td>'
            + '<td>' + c.credits + '</td>'
            + '<td>' + gl + '</td>'
            + '<td>' + (c.grade !== null ? (c.grade * c.credits).toFixed(1) : '—') + '</td></tr>';
        });

        acc += '</tbody></table></div></div></div>';
      });

      $('#historyAccordion').html(acc);
      $('#historyContent').removeClass('d-none');
    }, 'json').fail(function () {
      $('#historyLoading').addClass('d-none');
      $('#historyErrorMsg').text('Failed to load history.');
      $('#historyError').removeClass('d-none');
    });
  }

  function gradeToLetter(g) {
    if (g >= 4.0) return 'A';
    if (g >= 3.0) return 'B';
    if (g >= 2.0) return 'C';
    if (g >= 1.0) return 'D';
    return 'F';
  }

  function gpaInfo(gpa) {
    if (gpa === null) return { color: 'secondary', label: 'N/A' };
    gpa = parseFloat(gpa);
    if (gpa >= 3.7) return { color: 'success', label: 'Distinction' };
    if (gpa >= 3.0) return { color: 'info',    label: 'Merit' };
    if (gpa >= 2.0) return { color: 'warning',  label: 'Pass' };
    return             { color: 'danger',   label: 'Fail' };
  }

});
