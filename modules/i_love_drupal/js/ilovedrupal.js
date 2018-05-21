/**
 * Created by Ron on 30/04/2018.
 */
(function ($, Drupal) {
    Drupal.behaviors.myModuleBehavior = {
        attach: function (context, settings) {
            // This will be executed onload
            $('input[name=resetBtn]').on('click', function (e) {

                $('input[name=firstName]').val('')
                $('input[name=lastName]').val('')
                $('input[name=mobileNumber]').val('')
                $('input[name=email]').val('')
                $('input[name=company]').val('')

            })

            $('input[name=backBtn]').on('click', function (e) {

               window.location = drupalSettings.path.baseUrl+'/ilovedrupal'

            })
        }
    };
}(jQuery, Drupal, drupalSettings));