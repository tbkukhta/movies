/**
 * Show/hide #add-movies block
 */
$('#add-movies-button').on('click', function () {
  let $addMovies = $('#add-movies');
  if ($addMovies.hasClass('hidden')) {
    $addMovies.removeClass('hidden');
  } else {
    $addMovies.addClass('hidden');
    $('#add-movie-form').trigger('reset').find('input').removeClass('has-error').next('.error-block').text('');
    $('#from-file').val('').removeClass('has-error').nextAll('.error-block').text('');
  }
});

/**
 * Clear file-input value
 */
$('#delete-file-button').on('click', function () {
  $('#from-file').val('');
});

/**
 * Validate #from-file-form
 */
$('#from-file-submit').on('click', function (e) {
  let $file = $('#from-file');
  if ($file.val() === '') {
    e.preventDefault();
    $file.addClass('has-error');
    $file.nextAll('.error-block').text('Необходимо выбрать файл для загрузки.');
  } else {
    $file.removeClass('has-error').nextAll('.error-block').text('');
  }
});

/**
 * Validate #add-movie-form
 */
$('#add-movie-submit').on('click', function (e) {
  let $title = $('#movie-title');
  if ($title.val().trim() === '') {
    e.preventDefault();
    $title.addClass('has-error');
    $title.next('.error-block').text('Название должно содержать минимум 1 символ.');
  } else {
    $title.removeClass('has-error').next('.error-block').text('');
  }
  
  let $year = $('#movie-year');
  if (!$year.val().trim().match(/^(18[5-9]\d|19\d\d|200\d|201\d|2020)$/)) {
    e.preventDefault();
    $year.addClass('has-error');
    $year.next('.error-block').text('Неверный формат года.');
  } else {
    $year.removeClass('has-error').next('.error-block').text('');
  }
  
  let $stars = $('#movie-stars');
  if (!$stars.val().trim().match(/^([a-zа-яёєґії\s]+)([,\s][a-zа-яёєґії\s]+)*$/iu)) {
    e.preventDefault();
    $stars.addClass('has-error');
    $stars.next('.error-block').text('Неверный формат записи актёров.');
  } else {
    $stars.removeClass('has-error').next('.error-block').text('');
  }
});