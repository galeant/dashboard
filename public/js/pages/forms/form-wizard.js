$(function () {
    // //Horizontal form basic
    // $('#wizard_horizontal').steps({
    //     headerTag: 'h2',
    //     bodyTag: 'section',
    //     transitionEffect: 'slideLeft',
    //     onInit: function (event, currentIndex) {
    //         setButtonWavesEffect(event);
    //     },
    //     onStepChanged: function (event, currentIndex, priorIndex) {
    //         setButtonWavesEffect(event);
    //     }
    // });

    // //Vertical form basic
    // $('#wizard_vertical').steps({
    //     headerTag: 'h2',
    //     bodyTag: 'section',
    //     transitionEffect: 'slideLeft',
    //     stepsOrientation: 'vertical',
    //     onInit: function (event, currentIndex) {
    //         setButtonWavesEffect(event);
    //     },
    //     onStepChanged: function (event, currentIndex, priorIndex) {
    //         setButtonWavesEffect(event);
    //     }
    // });

    //Advanced form with validation
    // var form = $('#wizard_with_validation').show();
    // form.steps({
    //     headerTag: 'h3',
    //     bodyTag: 'fieldset',
    //     transitionEffect: 'slideLeft',
    //     onInit: function (event, currentIndex, newIndex) {
    //         // Allways allow previous action even if the current form is not valid!
    //         if (currentIndex > newIndex)
    //         {
    //             return true;
    //         }
    //         // Forbid next action on "Warning" step if the user is to young
    //         if (newIndex === 3 && Number($("#age-2").val()) < 18)
    //         {
    //             return false;
    //         }
    //         // Needed in some cases if the user went back (clean up)
    //         if (currentIndex < newIndex)
    //         {
    //             // To remove error styles
    //             form.find(".body:eq(" + newIndex + ") label.error").remove();
    //             form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
    //         }
    //         form.validate().settings.ignore = ":disabled,:hidden";
    //         return form.valid();
    //         // // $.AdminBSB.input.activate();

    //         // //Set tab width
    //         // var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
    //         // var tabCount = $tab.length;
    //         // $tab.css('width', (100 / tabCount) + '%');

    //         // //set button waves effect
    //         // // setButtonWavesEffect(event);
    //     },
    //     onStepChanging: function (event, currentIndex, newIndex) {
    //         if (currentIndex > newIndex) { return true; }

    //         if (currentIndex < newIndex) {
    //             form.find('.body:eq(' + newIndex + ') label.error').remove();
    //             form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
    //         }

    //         form.validate().settings.ignore = ':disabled,:hidden';
    //         return form.valid();
    //     },
    //     onStepChanged: function (event, currentIndex, priorIndex) {
    //         // Used to skip the "Warning" step if the user is old enough.
    //         if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
    //         {
    //             form.steps("next");
    //         }
    //         // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
    //         if (currentIndex === 2 && priorIndex === 3)
    //         {
    //             form.steps("previous");
    //         }
    //         // setButtonWavesEffect(event);
    //     },
    //     onFinishing: function (event, currentIndex) {
    //         form.validate().settings.ignore = ':disabled';
    //         return form.valid();
    //     },
    //     onFinished: function (event, currentIndex) {
    //         // swal("Good job!", "Submitted!", "success"); //alert with sweet alert
    //         $("#wizard_with_validation").submit(); //submit form
    //     }
    // });

    $('#wizard_with_validation').validate({
        highlight: function (input) {
            $(input).parents('.valid-info').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.valid-info').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.valid-info').append(error);
        },
        rules: {
            'confirm': {
                equalTo: '#password'
            }
        }
    });
});

// function setButtonWavesEffect(event) {
//     $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
//     $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
// }