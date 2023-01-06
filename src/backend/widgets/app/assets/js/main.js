(function ($) {
    $(document).ready(function () {
        $('#ajaxModal,.ajax__modal').on('shown.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var href = button.attr('href');
            if (!href && e.relatedTarget.localName.toLowerCase() !== 'a') {
                href = button.find('a').first().attr('href');
            }
            if (typeof href !== 'undefined') {
                var modal = $(this);
                modal.find('.modal-body').html("" +
                    "<div class='text-center'>" +
                    "<div class='spinner-border text-primary text-center' role='status'>" +
                    "<span class='visually-hidden'></span>" +
                    "</div>" +
                    "</div>\n"
                );
                if (button.data('header')) {
                    var header = button.data('header');
                } else {
                    header = '';
                }
                modal.find('.modal-header h4').text(header);
            }
            $.ajax({
                type: 'POST',
                url: href,
                success: function (response) {
                    modal.find('.modal-body').html(response);
                }
            });
        });
        $(document).on('hidden.bs.modal', '#ajaxModal', function () {
            $('.modal.in').length && $(document.body).addClass('modal-open');
        });

        $(document).on('click', '.confirm__popup', function (event) {
            return confirm($(this).data('message'));
        })

        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
}(jQuery));