$(document).ready(function(){

  // Init typed.js
  if(window.Typed){
    var typed4 = new Typed('#search', {
      strings: ['Online fashion store', 'Multi vendor market place', 'Customer service platform', 'Printing service'],
      typeSpeed: 50,
      backSpeed: 40,
      attr: 'placeholder',
      bindInputFocusEvents: true,
      loop: true
    });
  }

  // Show loader on submit
  $('#search-form').on('submit', function(){
    $('#search-loader').fadeIn();
  })

  // Logo image trigger
  $('#logo-trigger').click(function(){
    $('#logo-input').trigger('click');
  });

  // Logo image preview
  $('#logo-input').change(function(){
    const [file] = $(this)[0].files
    if (file) {
      $('#logo-preview').empty();
      $('#logo-preview').css('background-image', 'url(' + URL.createObjectURL(file) + ')');
    }
  });

  $('.close-notification').click(function(){
    $(this).closest('.notification').remove();
  });

  $('#search-filters-trigger').click(function(){
    $('#search-filters').slideToggle();
  });
});