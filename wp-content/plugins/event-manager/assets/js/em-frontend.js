jQuery(function ($) {
     var form = $('#em-event-form');
     var $resultDiv = $('#em-form-result');

     // Set min date/time to now for start and end
     function setMinDateTime() {
          var now = new Date();
          var pad = n => (n < 10 ? '0' : '') + n;
          var formatted = now.getFullYear()
               + '-' + pad(now.getMonth() + 1)
               + '-' + pad(now.getDate())
               + 'T' + pad(now.getHours())
               + ':' + pad(now.getMinutes());
          $('#em_duration_start, #em_duration_end').attr('min', formatted);
     }
     setMinDateTime();

     $('#em_duration_start').on('change', function () {
          var startVal = $(this).val();
          if (startVal) {
               $('#em_duration_end').attr('min', startVal);
          } else {
               setMinDateTime();
          }
     });

     // REGEX for validation
     var urlRegex = /^(https?:\/\/)?([\w\-]+\.)+\w{2,}(\/[^\s]*)?$/i;
     var phoneRegex = /^[\d\s\-\+\(\)]+$/;

     // Validation rules (returns '' if valid, or error string)
     function validateField(name, value) {
          value = (typeof value === 'string') ? value.trim() : value;

          switch (name) {
               case 'em_title':
                    return value === '' ? 'Please enter the event title.' : '';
               case 'em_content':
                    return value === '' ? 'Please enter a description.' : '';
               case 'em_event_type':
                    return value === '' ? 'Please select an event type.' : '';
               case 'em_duration_start':
                    if (!value) return 'Start date/time is required.';
                    if (new Date(value) < new Date()) return 'Start date/time cannot be in the past.';
                    return '';
               case 'em_duration_end':
                    var start = form.find('[name="em_duration_start"]').val();
                    if (!value) return 'End date/time is required.';
                    if (start && new Date(value) <= new Date(start)) return 'End date/time must be after start.';
                    return '';
               case 'em_organizer':
                    return value === '' ? 'Please enter the organizer name.' : '';
               case 'em_organizer_contact':
                    if (value === '') return 'Please enter organizer contact info.';
                    if (!phoneRegex.test(value)) return 'Please enter a valid phone number (digits, spaces, +, -, () allowed).';
                    return '';
               case 'em_location':
                    return value === '' ? 'Please enter the event location.' : '';
               case 'em_city':
               case 'em_city_text':
                    var cityDropdown = form.find('[name="em_city"]');
                    var cityText = form.find('[name="em_city_text"]');
                    if (cityDropdown.length && cityDropdown.val()) return '';
                    if (cityText.length && cityText.val().trim() !== '') return '';
                    return 'Please select or enter a city.';
               case 'em_conference_website':
                    if (form.find('#em_event_type').val() === 'conference') {
                         if (value === '') return 'Please enter the conference website.';
                         if (!urlRegex.test(value)) return 'Please enter a valid website URL (must be http(s)://...)';
                    }
                    return '';
               case 'em_workshop_level':
                    if (form.find('#em_event_type').val() === 'workshop') {
                         if (value === '') return 'Please select the workshop level.';
                    }
                    return '';
               case 'em_photo':
                    if (value && value.files && value.files[0]) {
                         var file = value.files[0];
                         var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                         if ($.inArray(file.type, allowedTypes) === -1) return 'Photo must be a JPG, PNG, or GIF image.';
                         if (file.size > 2 * 1024 * 1024) return 'Photo must be less than 2MB.';
                    }
                    return '';
               default:
                    return '';
          }
     }

     function showError(field, message) {
          var $el = form.find('[name="' + field + '"]');
          $el.next('.em-error').remove();
          if (message)
               $el.after('<span class="em-error" style="color:red;display:block;">' + message + '</span>');
     }

     function hideError(field) {
          var $el = form.find('[name="' + field + '"]');
          $el.next('.em-error').remove();
     }

     // Real-time validation on relevant fields
     form.on('blur change input', 'input, textarea, select', function () {
          var name = $(this).attr('name');
          if (!name) return;
          var value = (name === 'em_photo') ? this : $(this).val();
          var error = validateField(name, value);
          if (error) showError(name, error);
          else hideError(name);
     });

     // Conditional fields for event type
     $('#em_event_type').on('change', function () {
          $('.em-conditional-fields').hide();
          if (this.value === 'conference') $('#em-type-conference').show();
          else if (this.value === 'workshop') $('#em-type-workshop').show();
     });

     // Submit handler
     form.on('submit', function (e) {
          e.preventDefault();

          var hasError = false;
          $('.em-error').remove();
          $resultDiv.empty();

          // Validate all visible fields
          form.find('input, textarea, select').each(function () {
               var name = $(this).attr('name');
               if (!name) return;
               // Skip hidden conditional fields
               if ($(this).closest('.em-conditional-fields').length && !$(this).closest('.em-conditional-fields').is(':visible')) return;
               var value = (name === 'em_photo') ? this : $(this).val();
               var error = validateField(name, value);
               if (error) {
                    showError(name, error);
                    hasError = true;
               }
          });

          if (hasError) {
               $resultDiv.html('<div class="error-msg">Please fix the errors below.</div>');
               return;
          }

          var formData = new FormData(this);
          formData.append('action', 'em_submit_event');
          formData.append('nonce', em_ajax.nonce);

          $.ajax({
               url: em_ajax.ajax_url,
               method: 'POST',
               data: formData,
               processData: false,
               contentType: false,
               success: function (response) {
                    $('.em-error').remove();
                    if (response.success) {
                         $resultDiv.html('<div class="success-msg">' + response.data.message + '</div>');
                         form[0].reset();
                         $('.em-conditional-fields').hide();
                    } else if (response.data) {
                         // Show server-side errors
                         $.each(response.data, function (field, msg) {
                              showError(field, msg);
                         });
                         if (response.data.global)
                              $resultDiv.html('<div class="error-msg">' + response.data.global + '</div>');
                         else
                              $resultDiv.html('<div class="error-msg">Please fix the errors below.</div>');
                    }
               },
               error: function () {
                    $resultDiv.html('<div class="error-msg">Submission failed. Please try again.</div>');
               }
          });
     });
});