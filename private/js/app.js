import 'semantic-ui-css/components/accordion';
import 'semantic-ui-css/components/checkbox';
import 'semantic-ui-css/components/dimmer';
import 'semantic-ui-css/components/dropdown';
import 'semantic-ui-css/components/rating';
import 'semantic-ui-css/components/sidebar';
import 'semantic-ui-css/components/tab';
import 'semantic-ui-css/components/transition';
import $ from 'jquery';

import 'sylius/ui/sylius-api-login'
import 'sylius/ui/sylius-api-toggle'
import 'sylius/ui/sylius-auto-complete'
import 'sylius/ui/sylius-bulk-action-require-confirmation'
import 'sylius/ui/sylius-form-collection'
import 'sylius/ui/sylius-prototype-handler'
import 'sylius/ui/sylius-require-confirmation'
import 'sylius/ui/sylius-toggle'
import 'sylius/ui/sylius-auto-complete'

$(document).ready(() => {
    // ### SYLIUS ###
    $('#sidebar').addClass('visible');
    $('#sidebar').sidebar('attach events', '#sidebar-toggle', 'toggle');
    $('#sidebar').sidebar('setting', {
        dimPage: false,
        closable: false,
    });

    $('.ui.checkbox').checkbox();
    $('.ui.accordion').accordion();
    $('.ui.menu .dropdown').dropdown({ action: 'hide' });
    $('.ui.inline.dropdown').dropdown();
    $('.link.ui.dropdown').dropdown({ action: 'hide' });
    $('.button.ui.dropdown').dropdown({ action: 'hide' });
    $('.ui.fluid.search.selection.ui.dropdown').dropdown();
    $('.ui.tabular.menu .item, .sylius-tabular-form .menu .item').tab();
    $('.ui.card .dimmable.image, .ui.cards .card .dimmable.image').dimmer({ on: 'hover' });
    $('.ui.rating').rating('disable');

    $('form.loadable button[type=submit]').on('click', (event) => {
        $(event.currentTarget).closest('form').addClass('loading');
    });
    $('.loadable.button').on('click', (event) => {
        $(event.currentTarget).addClass('loading');
    });
    $('.message .close').on('click', (event) => {
        $(event.currentTarget).closest('.message').transition('fade');
    });

    $('[data-requires-confirmation]').requireConfirmation();
    $('[data-bulk-action-requires-confirmation]').bulkActionRequireConfirmation();
    $('[data-toggles]').toggleElement();
    $('.sylius-autocomplete').autoComplete();

    $('.special.cards .image').dimmer({
        on: 'hover',
    });

    $('[data-form-type="collection"]').CollectionForm();

    // ### CUSTOM ###
});
