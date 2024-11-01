var editor_css = null,
    editor_js = null

jQuery(document).ready(function ($) {

    $('.tus-nav-tab').on('click', function (e) {
        e.preventDefault();

        $('.tus-nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        var rel = $(this).attr('rel');
        $('.tus-tab-content').hide();
        $('.tus-tab-content.' + rel).show();

        if (rel == "styling" && !editor_css) {

            list_of_css = document.getElementsByClassName('css-scripting');
            for (let index = 0; index < list_of_css.length; index++) {
                const element = list_of_css[index];
                editor_css = wp.codeEditor.initialize(element, {
                    className: 'auto-height',
                    codemirror: {
                        readOnly: true,
                        mode: "text/css",
                        lineWrapping: false,
                        hint: false, // Disable autocomplete
                        showHint: false // Disable suggestion popups                
                    }
                });
            }
            setTimeout(() => {
                for (let index = 0; index < list_of_css.length; index++) {
                    const element = list_of_css[index];
                    element.nextSibling.classList.add('auto-height');
                }
            }, 100);

        }
        if (rel == "scripting" && !editor_js) {

            list_of_js = document.getElementsByClassName('js-scripting');
            for (let index = 0; index < list_of_js.length; index++) {
                const element = list_of_js[index];
                editor_js = wp.codeEditor.initialize(element, {
                    className: 'auto-height',
                    codemirror: {
                        readOnly: true,
                        lineWrapping: false,
                        hint: false, // Disable autocomplete
                        showHint: false // Disable suggestion popups                
                    },
                });
            }

            setTimeout(() => {
                for (let index = 0; index < list_of_js.length; index++) {
                    const element = list_of_js[index];
                    element.nextSibling.classList.add('auto-height');
                }
            }, 100);
        }
    });

    $('#validate_btn').click(function () {
        $('#submit').click();
    });

    $('input[type="radio"][name="salesforce_on"]').change(function () {
        if (this.value == '1') {
            $('#salesforce-survey-id-wrapper').slideDown();
        } else {
            $('#salesforce-survey-id-wrapper').slideUp();
        }
    });

    $('input[type=radio][name=builder_type]').change(function() {
        var elm = this;
        ['web', 'form'].forEach(function(item) {
            if (elm.value == item) {
                $('.builder_type_' + item + '_more_info').show();
            } else {
                $('.builder_type_' + item + '_more_info').hide();
            }
        });
        updateShortcode();
    });
    $('input[type=radio][name=builder_display]').change(function() {
        var elm = this;
        ['auto', 'method', 'event'].forEach(function(item) {
            if (elm.value == item) {
                $('.builder_display_' + item + '_more_info').show();
            } else {
                $('.builder_display_' + item + '_more_info').hide();
            }
        });
        updateShortcode();
    });
    $('input[type=radio][name=builder_delay]').change(function() {
        var elm = this;
        ['yes', 'no'].forEach(function(item) {
            if (elm.value == item) {
                $('.builder_delay_' + item + '_more_info').show();
            } else {
                $('.builder_delay_' + item + '_more_info').hide();
            }
        });
        updateShortcode();
    });

    $('.builder_delay_add_milliseconds').on("click", function(e) {
        e.preventDefault();
        var ms = $(this).attr("rel");
        $('#builder_delay_milliseconds').val(ms);
        updateShortcode();
    });

    $('#builder_survey_id, #builder_display_event_id_class, #builder_delay_milliseconds').on('keyup', function() {
        updateShortcode();
    })

    var updateShortcode = function() {
        var final_shortcode = '',
            survey_id = $('#builder_survey_id').val().trim(),
            type = $('input[type=radio][name=builder_type]:checked').val(),
            display = $('input[type=radio][name=builder_display]:checked').val(),
            display_value = $('#builder_display_event_id_class').val(),
            delay = $('input[type=radio][name=builder_delay]:checked').val(),
            delay_value = $('#builder_delay_milliseconds').val();

        if (survey_id !== '') {
            final_shortcode = `[thumbsupsurvey-${type} license="${survey_id}"`;

            switch (display) {
                case 'method':
                    final_shortcode += ' wait-for-method="1"';
                    break;
            
                case 'event':
                    var extra_method_description = '(waiting for more info)';
                    if (display_value !== '') {
                        if (!display_value.startsWith('#') && !display_value.startsWith('.')) {
                            display_value = '.' + display_value;
                        }

                        if (display_value.startsWith('#')) {
                            extra_method_description = `ID [<strong>${display_value}</strong>]`;
                        } else {
                            extra_method_description = `class [<strong>${display_value}</strong>]`;
                        }
                        
                        final_shortcode += ` wait-for-click="${display_value}"`;
                    }
                    $('#builder_display_event_more_info_details').html(`You will need to add ${extra_method_description} on the element which will be used to trigger the survey.`);
                    break;
            
                default:
                    break;
            }

            var extra_delay_description = 'Default option, survey is loaded instantly when triggered.';
            if (delay === "yes") {
                let v = parseInt(delay_value);
                if (isNaN(v) || v < 0) {
                  v = 0;
                }
                if (v > 0) {
                    final_shortcode += ` wait-for-time="${v}"`;
                    extra_delay_description = `Survey is displayed with a delay of ${v.toLocaleString()} milliseconds.`;
                }
          
            }
            $('#builder_delay_more_info_details').html(extra_delay_description);

            final_shortcode += ']';
        }

        $('#builder_survey_shortcode').val(final_shortcode);
    }
    

});
