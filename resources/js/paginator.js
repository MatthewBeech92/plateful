(function ($) {
    $.fn.paginate = function (options) {
        var defaultOptions = {
            pageSize: 10,
            pageRange: 3,
            showFirst: false,
            showLast: false,
        };
        var opts = $.extend({}, defaultOptions, options);
        var paginator = this;
        var paginationElement;
        var pageSize = opts.pageSize;
        var pageRange = opts.pageRange;
        var showFirst = opts.showFirst;
        var showLast = opts.showLast;
        var dataObj = opts.dataObj;
        var displayData = opts.displayData;
        var currentPageIndex = 0;
        var pagesArray = [];
        var totalPages;

        if (typeof dataObj === 'function') {
            dataObj(function (data) {
                dataObj = data;
                generatePagination();
                showData();
            });
        }

        function generatePagination() {
            totalPages = Math.ceil(dataObj.length / pageSize);

            paginator.html('<ul class="pagination" />');
            paginationElement = paginator.children('.pagination');

            for (var i = 1; i <= totalPages; i++) {
                pagesArray.push(i);
            }

            if (totalPages <= 1) {
                paginator.children('.pagination').remove();
            } else {
                displayPaginator();
            }
        }

        function showData() {
            var startIndex = currentPageIndex * pageSize;
            var endIndex = startIndex + pageSize;
            var data = dataObj.slice(startIndex, endIndex);

            if ($('.page').length === 0) {
                displayData(dataObj);
            } else {
                displayData(data);
            }
        }

        function displayPaginator(el) {
            var paginateArray;
            var startIndex;

            paginationElement.children().remove();

            // update currentPageIndex variable
            updateCurrentPageIndex(el);

            startIndex = Math.min(totalPages + 1 - pageRange, Math.max(1, currentPageIndex + 1 - Math.floor(pageRange / 2))) - 1;
            if (Math.sign(startIndex) < 0) {
                startIndex = 0;
            }

            paginateArray = pagesArray.slice(startIndex, startIndex + pageRange);

            $.each(paginateArray, function (_index, pageNum) {
                $('<li class="page"> ' + pageNum + ' </li>').appendTo(paginationElement);
            });

            $('<li class="chevron pagination-prev"></li>').insertBefore(paginationElement.children('.page:first-child'));
            $('<li class="chevron pagination-next"></li>').insertAfter(paginationElement.children('.page:last-child'));

            if (showFirst) {
                $(paginationElement).prepend($('<li class="chevron pagination-first"></li>'));
            }

            if (showLast) {
                $(paginationElement).append($('<li class="chevron pagination-last"></li>'));
            }

            $(paginationElement)
                .find('.page:contains("' + (currentPageIndex + 1) + '")')
                .addClass('active');

            disable();
        }

        function disable() {
            $(paginator).children('.pagination li').removeClass('disabled');

            if (paginationElement.length) {
                if (currentPageIndex === 0) {
                    paginationElement.children('.active').prevAll().addClass('disabled');
                } else if (currentPageIndex === totalPages - 1) {
                    paginationElement.children('.active').nextAll().addClass('disabled');
                }
            }
        }

        function updateCurrentPageIndex(el) {
            if (typeof el !== 'undefined') {
                if (el.hasClass('pagination-next')) {
                    currentPageIndex = currentPageIndex + 1;
                } else if (el.hasClass('pagination-prev')) {
                    currentPageIndex = currentPageIndex - 1;
                } else if (el.hasClass('pagination-last')) {
                    currentPageIndex = totalPages - 1;
                } else if (el.hasClass('pagination-first')) {
                    currentPageIndex = 0;
                } else if (el.html() !== '') {
                    currentPageIndex = parseInt(el.html() - 1);
                }
            }
        }

        $(document)
            .find(paginator)
            .on('click', '.page', function () {
                displayPaginator($(this));
                showData();
            });

        $(document)
            .find(paginator)
            .on('click', '.pagination-first', function () {
                if (!$(this).hasClass('disabled')) {
                    displayPaginator($(this));
                    showData();
                }
            });

        $(document)
            .find(paginator)
            .on('click', '.pagination-prev', function () {
                if (!$(this).hasClass('disabled')) {
                    displayPaginator($(this));
                    showData();
                }
            });

        $(document)
            .find(paginator)
            .on('click', '.pagination-next', function () {
                if (!$(this).hasClass('disabled')) {
                    displayPaginator($(this));
                    showData();
                }
            });

        $(document)
            .find(paginator)
            .on('click', '.pagination-last', function () {
                if (!$(this).hasClass('disabled')) {
                    displayPaginator($(this));
                    showData();
                }
            });
    };
})(jQuery);
