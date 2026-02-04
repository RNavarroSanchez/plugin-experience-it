(function ($) {
    'use strict';

    var currentPage = 1;

    function getFilters() {
        return {
            name: $('#ula-name').val() || '',
            surname: $('#ula-surname').val() || '',
            email: $('#ula-email').val() || ''
        };
    }

    function renderRows(items) {
        var $tbody = $('#ula-table-body');
        $tbody.empty();

        if (!items.length) {
            $tbody.append('<tr><td colspan="5">No hay resultados</td></tr>');
            return;
        }

        items.forEach(function (user) {
            var row = [
                '<tr>',
                '<td>' + user.username + '</td>',
                '<td>' + user.name + '</td>',
                '<td>' + user.surname1 + '</td>',
                '<td>' + user.surname2 + '</td>',
                '<td>' + user.email + '</td>',
                '</tr>'
            ].join('');
            $tbody.append(row);
        });
    }

    function renderPagination(totalPages, page) {
        var $pagination = $('#ula-pagination');
        $pagination.empty();

        if (totalPages <= 1) {
            return;
        }

        var createButton = function (label, targetPage, disabled, active) {
            var $btn = $('<button type="button" class="button ula-page"></button>');
            $btn.text(label);
            $btn.data('page', targetPage);
            if (disabled) {
                $btn.prop('disabled', true);
            }
            if (active) {
                $btn.addClass('is-active');
            }
            $pagination.append($btn);
        };

        createButton('Anterior', page - 1, page === 1, false);

        for (var i = 1; i <= totalPages; i++) {
            createButton(i, i, false, i === page);
        }

        createButton('Siguiente', page + 1, page === totalPages, false);
    }

    function fetchUsers(page) {
        var filters = getFilters();

        $.post(ulaAjax.ajaxUrl, {
            action: 'ula_fetch_users',
            nonce: ulaAjax.nonce,
            page: page,
            per_page: ulaAjax.perPage,
            name: filters.name,
            surname: filters.surname,
            email: filters.email
        }).done(function (response) {
            if (!response || !response.success) {
                return;
            }

            var data = response.data;
            currentPage = data.page;
            renderRows(data.items || []);
            renderPagination(data.total_pages || 0, data.page || 1);
        });
    }

    function debounce(fn, delay) {
        var timerId;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(timerId);
            timerId = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }

    var debouncedSearch = debounce(function () {
        currentPage = 1;
        fetchUsers(currentPage);
    }, 300);

    $(document).on('input', '#ula-name, #ula-surname, #ula-email', function () {
        debouncedSearch();
    });

    $(document).on('submit', '#ula-search-form', function (event) {
        event.preventDefault();
    });

    $(document).on('click', '#ula-clear', function () {
        $('#ula-name').val('');
        $('#ula-surname').val('');
        $('#ula-email').val('');
        currentPage = 1;
        fetchUsers(currentPage);
    });

    $(document).on('click', '.ula-page', function () {
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            fetchUsers(page);
        }
    });

    $(function () {
        fetchUsers(currentPage);
    });
})(jQuery);
