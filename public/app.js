var app = {};

if (window.jQuery === undefined) jQuery = $ = {};

!(function ($, window, document) {
    "use strict";

    $(function () {
        $('.app-table > table').DataTable({
            'pageLength': 10
        });
    });

})(window.jQuery, window, document);