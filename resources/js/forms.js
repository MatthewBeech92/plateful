$(function () {
    $(document).on('click', '.fieldset-next', function () {
        $(this).parents('fieldset').hide();
        $(this).parents('fieldset').next().show();
        $('.fieldset-back').show();
        setProgressBar();
    });

    $(document).on('click', '.fieldset-back', function (evt) {
        var activeFieldset = $(this).parents('fieldset');

        evt.preventDefault();
        activeFieldset.hide();
        activeFieldset.prev().show();

        console.log(activeFieldset.prev().index());
        if (activeFieldset.prev().hasClass('form-step-1')) {
            $('.back-btn').addClass('d-none');
        }
        setProgressBar();
    });

    // Custom Checkboxes
    $('span[role="checkbox"]').on('click', function () {
        console.log($('#remember-me').prop('checked'));
    });

    // Custom selects
    $('.custom-select select').each(function () {
        var defaultSelect = $(this);
        var customSelect = generateCustomSelect(defaultSelect);
        var listbox = customSelect.next();
        var listboxOptions = listbox.children();

        // Open and close listbox menu
        customSelect.on('click', function (evt) {
            evt.stopPropagation();

            toggleListboxVisibility(customSelect, listbox);
        });

        // Open listbox menu when user presses enter key on custom select
        customSelect.on('keyup', function (evt) {
            if (evt.key === 'Enter') {
                removeOptionStyles(listbox);
                toggleListboxVisibility(customSelect, listbox);
                selectOption(listboxOptions.first(), customSelect);
                listboxOptions.first().addClass('option-focused');
            }
        });

        listbox.on('keyup', function (evt) {
            evt.preventDefault();

            switch (evt.key) {
                case 'Down':
                case 'ArrowDown':
                case 'Up':
                case 'ArrowUp':
                    navigateListbox(evt, listboxOptions);
                    selectOption($('.option-focused'), customSelect);
                    break;
                case 'Enter':
                    selectOption($('.option-focused'), customSelect);
                    toggleListboxVisibility(customSelect, listbox);
                    listboxOptions.removeClass('option-focused');
                    break;
                case 'Esc':
                case 'Escape':
                    toggleListboxVisibility(customSelect, listbox);
            }
        });

        // When user hovers over listbox option, switch the focus to that option
        listboxOptions.on('mouseover', function (evt) {
            switchFocus($(this));
        });

        // When user selects an option
        listboxOptions.on('click', function (evt) {
            evt.stopPropagation();

            removeOptionStyles(listbox);
            selectOption($(this), customSelect);
            toggleListboxVisibility(customSelect, listbox);
        });
    });

    // Custom Multiple selects
    $('.multiple-select select').each(function () {
        var defaultSelect = $(this);
        var customSelect = generateCustomSelect(defaultSelect);
        var listbox = customSelect.next();
        var listboxOptions = listbox.children();

        // Open and close listbox menu
        customSelect.on('click', function (evt) {
            evt.stopPropagation();

            if ($(evt.target).hasClass('cross-icon-white')) {
                deleteOptionFromMultiSelect(evt, defaultSelect, customSelect, listboxOptions);
            } else {
                toggleListboxVisibility(customSelect, listbox);
            }
        });

        // Open listbox menu when user presses enter on custom select. If cross icon is targeted instead, delete the option.
        customSelect.on('keyup', function (evt) {
            if (evt.key === 'Enter') {
                if ($(evt.target).hasClass('cross-icon-white')) {
                    deleteOptionFromMultiSelect(evt, defaultSelect, customSelect, listboxOptions);
                } else {
                    toggleListboxVisibility(customSelect, listbox);
                    listboxOptions.first().addClass('option-focused');
                }
            }
        });

        // Navigate custom select on Keyup
        listbox.on('keyup', function (evt) {
            var isMultipleSelect = isMultiSelectElement(defaultSelect);
            var activeOption = $('.option-focused');

            switch (evt.key) {
                case 'Down':
                case 'ArrowDown':
                case 'Up':
                case 'ArrowUp':
                    navigateListbox(evt, listboxOptions, isMultipleSelect);
                    break;
                case 'Enter':
                    if (activeOption.length > 0) {
                        if (!activeOption.hasClass('option-selected')) {
                            toggleListboxVisibility(customSelect, listbox);
                            selectOption(activeOption, customSelect);
                            listboxOptions.removeClass('option-focused');
                        }
                    }
                    break;
                case 'Esc':
                case 'Escape':
                    toggleListboxVisibility(customSelect, listbox);
                    listboxOptions.removeClass('option-focused');
                    break;
                case ' ':
                case 'Spacebar':
                    selectOption($('.option-focused'), customSelect);
            }
        });

        // When user hovers over listbox option, switch the focus to that option
        listboxOptions.on('mouseover', function (evt) {
            switchFocus($(this));
        });

        // When user selects an option
        listboxOptions.on('click', function (evt) {
            evt.stopPropagation();

            if (!$(this).hasClass('option-selected')) {
                selectOption($(this), customSelect);
                toggleListboxVisibility(customSelect, listbox);
            }
        });
    });

    // Button Style Selects
    $('.btn-select select').each(function () {
        var defaultSelect = $(this);
        var btnSelect = generateCustomSelect(defaultSelect);
        var listbox = btnSelect.next();
        var listboxOptions = listbox.children();

        // Open and close listbox menu
        btnSelect.on('click', function (evt) {
            evt.stopPropagation();

            toggleListboxVisibility(btnSelect, listbox);
        });

        // Open listbox menu when user presses enter on button select. If cross icon is targeted instead, delete the option.
        btnSelect.on('keyup', function (evt) {
            if (evt.key === 'Enter') {
                toggleListboxVisibility(btnSelect, listbox);
                listboxOptions.first().addClass('option-focused');
            }
        });

        // Navigate custom select on Keyup
        listbox.on('keyup', function (evt) {
            var activeOption = $('.option-focused');

            switch (evt.key) {
                case 'Down':
                case 'ArrowDown':
                case 'Up':
                case 'ArrowUp':
                    navigateListbox(evt, listboxOptions);
                    break;
                case 'Enter':
                    if (activeOption.length > 0) {
                        if (!activeOption.hasClass('option-selected')) {
                            toggleListboxVisibility(btnSelect, listbox);
                            listboxOptions.removeClass('option-focused');
                        }
                    }
                    break;
                case 'Esc':
                case 'Escape':
                    toggleListboxVisibility(btnSelect, listbox);
                    listboxOptions.removeClass('option-focused');
                    break;
            }
        });

        // When user hovers over listbox option, switch the focus to that option
        listboxOptions.on('mouseover', function (evt) {
            switchFocus($(this));
        });

        // When user selects an option
        listboxOptions.on('click', function (evt) {
            evt.stopPropagation();

            if (!$(this).hasClass('option-selected')) {
                toggleListboxVisibility(btnSelect, listbox);
            }
        });
    });

    /*
     *
     * Combobox Functions
     *
     */

    $('.combobox-search').on('click', function (evt) {
        evt.stopPropagation();

        // Hide other open listboxes
        $('.listbox').each(function () {
            hideListbox($(this));
        });

        // If listbox has options then show the options
        if ($(this).next().children().length !== 0) {
            showComboboxListbox($(this).parent());
        }
    });

    $('.combobox-search').on('keyup', function (evt) {
        var listbox = $(this).next('.listbox');
        var listboxOptions = listbox.children();

        switch (evt.key) {
            case 'Down':
            case 'ArrowDown':
            case 'Up':
            case 'ArrowUp':
                navigateListbox(evt, listboxOptions);
        }

        if ($(this).val().length < 2) {
            hideListbox(listbox);
            listbox.remove();
        }
    });

    $(document).on('mouseover', '.combobox-search ~ ul li   ', function (evt) {
        switchFocus($(this));
    });

    $(document).on('click', '.combobox-search ~ ul li   ', function (evt) {
        var listbox = $(this).parent();

        evt.stopPropagation();
        hideListbox(listbox);
    });

    /*
     *
     * Listbox Functions
     *
     */

    // if a user clicks anywhere on the page, listbox is hidden
    $(document).on('click', function () {
        hideListbox($('.listbox'));
    });

    $(document).on('mouseleave', '.listbox', function (evt) {
        $(this).children().removeClass('option-focused');
    });

    $('.listbox').on('keydown', function (evt) {
        var formElement = $(this).prev();

        // Toggle active listbox if user presses the tab key
        if (evt.key === 'Tab') {
            toggleListboxVisibility(formElement, $(this));
            $(this).children().removeClass('option-focused');
        }
        // This will prevent scrolling when user uses arrow keys to navigate listbox menu
        preventPageScroll(evt);
    });

    function selectOption(listboxOption, formElement) {
        var defaultFormElement = formElement.prev('select');

        if (defaultFormElement.attr('multiple') === 'multiple') {
            if (listboxOption.hasClass('option-selected')) {
                return;
            } else {
                generateChip(listboxOption).appendTo(formElement.children('ul'));
                defaultFormElement.children('option[value="' + listboxOption.data('optionValue') + '"]').prop('selected', true);
            }
        } else {
            formElement.text(listboxOption.text());
            defaultFormElement.val(listboxOption.attr('data-option-value'));
        }

        listboxOption.addClass('option-selected');
        listboxOption.attr('aria-selected', 'true');
        listboxOption.parent().attr('aria-activedescendant', listboxOption.attr('id'));
    }

    function navigateListbox(evt, listboxOptions, isMultipleSelect) {
        var listbox = listboxOptions.parent();
        var optionInFocus = $('.option-focused');
        var numberOfOptions = listboxOptions.length - 1;

        switch (evt.key) {
            case 'Down':
            case 'ArrowDown':
                if (listboxOptions.hasClass('option-focused')) {
                    removeOptionStyles(listbox, isMultipleSelect);

                    if (optionInFocus.index() === numberOfOptions) {
                        listboxOptions.first().addClass('option-focused');
                    } else {
                        optionInFocus.next().addClass('option-focused');
                    }
                } else {
                    removeOptionStyles(listbox, isMultipleSelect);
                    listboxOptions.first().addClass('option-focused');
                }
                scroll(evt, listboxOptions);
                break;
            case 'Up':
            case 'ArrowUp':
                if (listboxOptions.hasClass('option-focused')) {
                    removeOptionStyles(listbox, isMultipleSelect);

                    if (optionInFocus.index() === 0) {
                        listboxOptions.last().addClass('option-focused');
                    } else {
                        optionInFocus.prev().addClass('option-focused');
                    }
                } else {
                    removeOptionStyles(listbox, isMultipleSelect);
                    listboxOptions.last().addClass('option-focused');
                }
                scroll(evt, listboxOptions);
                break;
        }
    }

    function toggleListboxVisibility(formElement, listbox) {
        // Reset scroll position of listbox menu
        listbox.scrollTop(0);

        // Hide listbox menu from other form elements that are expanded
        $('.listbox')
            .not(listbox)
            .each(function () {
                hideListbox($(this));
            });

        listbox.toggle();
        formElement.toggleClass('listbox-active');

        if (formElement.hasClass('listbox-active')) {
            listbox.trigger('focus');
            formElement.attr('aria-expanded', 'true');
        } else {
            formElement.trigger('focus');
            formElement.attr('aria-expanded', 'false');
        }
    }

    function removeOptionStyles(listbox, isMultipleSelect) {
        if (isMultipleSelect) {
            listbox.children().removeClass('option-focused');
        } else {
            listbox.children().removeClass('option-selected');
            listbox.children().removeClass('option-focused');
            listbox.children().attr('aria-selected', 'false');
        }
    }

    function hideListbox(listbox) {
        var listboxOptions = listbox.children();

        listbox.hide();
        listboxOptions.removeClass('option-focused');
    }

    /*
     *
     * General Functions
     *
     */

    function generateCustomSelect(defaultSelect) {
        var selectId = defaultSelect.attr('name').replaceAll('_', '-').replace('[]', '');
        var labelId = defaultSelect.prev('label').attr('id');
        var selectedOption;

        // Create div that will act as the select button
        var customSelect = $('<div />', {
            id: selectId,
            class: 'styled-select',
            tabindex: '0',
            'aria-labelledby': labelId,
            'aria-haspopup': 'listbox',
            'aria-expanded': 'false',
        }).insertAfter(defaultSelect);

        // Create ul for listbox list and append list items
        var optionList = $('<ul />', {
            class: 'listbox',
            role: 'listbox',
            tabindex: '-1',
            'aria-labelledby': labelId,
        }).insertAfter(customSelect);

        // Generate list items and attach to optionList
        $.each(defaultSelect.children('option'), function (ind, val) {
            $('<li />', {
                text: defaultSelect.children('option').eq(ind).text(),
                id: selectId + '-' + ind,
                'data-option-value': defaultSelect.children('option').eq(ind).val(),
                role: 'option',
            }).appendTo(optionList);
        });

        // Create chip list and set aria attributes for multi-select fields
        if (defaultSelect.attr('multiple')) {
            $('<ul class="chip-list"></ul>').appendTo(customSelect);
            optionList.attr('aria-multiselectable', true);
            optionList.children().attr('aria-selected', false);
        }

        // See if select already has selected options and select them
        if (defaultSelect.attr('data-selected-option')) {
            selectedOption = defaultSelect.attr('data-selected-option');

            if (defaultSelect.attr('multiple')) {
                selectedOption = selectedOption.split('/');
                $.each(selectedOption, function (key, value) {
                    findAndSelectChosenOption(optionList.children(), value, customSelect);
                });
            } else {
                findAndSelectChosenOption(optionList.children(), selectedOption, customSelect);
            }
        }

        // Remove first option which if it's blank
        if (optionList.children().first().text() === '') {
            optionList.children().first().remove();
        } else {
            selectOption(optionList.children().first(), customSelect);
        }

        return customSelect;
    }

    function findAndSelectChosenOption(options, optionName, customSelect) {
        $.each(options, function () {
            if ($(this).attr('data-option-value') === optionName) {
                selectOption($(this), customSelect);
            }
        });
    }

    function switchFocus(optionInFocus) {
        optionInFocus.addClass('option-focused').siblings().removeClass('option-focused');
    }

    function generateChip(listboxOption) {
        return $('<li />', {
            class: 'chip',
            value: listboxOption.data('optionValue'),
            html: [
                $('<span />', {
                    text: listboxOption.text(),
                }),
                $('<div />', {
                    class: 'btn-icon cross-icon-white',
                    tabindex: '0',
                    'aria-label': 'Delete ' + listboxOption.text() + ' recipe type',
                }),
            ],
        });
    }

    function isMultiSelectElement(select) {
        if (select.attr('multiple') === 'multiple') {
            return true;
        }
    }

    // When custom select overflows scroll listbox on press of arrow keys
    function scroll(evt, options) {
        console.log(options);
        var listbox = options.parent();
        var optionPositionTop = $('.option-focused').position().top + listbox.scrollTop();
        var optionPositionBottom = $('.option-focused').position().top + listbox.scrollTop() + $('.option-focused').outerHeight();
        var lastOptionPositionBottom = options.last().position().top + listbox.scrollTop() + $('.option-focused').outerHeight();

        if (evt.key === 'Down' || evt.key === 'ArrowDown') {
            if (optionPositionBottom > listbox.height() + listbox.scrollTop()) {
                listbox.scrollTop(optionPositionBottom - listbox.height() + 8);
            } else if (options.first().hasClass('option-focused')) {
                listbox.scrollTop(0);
            }
        } else {
            if (optionPositionTop < listbox.scrollTop()) {
                listbox.scrollTop(optionPositionTop - 8);
            } else if (options.last().hasClass('option-focused')) {
                listbox.scrollTop(lastOptionPositionBottom);
            }
        }

        // When listbox is scrolled via keyboard input disable mouseenter event.
        // This prevents an issue where if mouse is hovering an option when scroll is triggered the option hovered would gain .focused class.
        options.off('mouseover');
        options.one('mousemove', function () {
            options.css('cursor', 'pointer');
            switchFocus($(this));

            options.on('mouseover', function () {
                switchFocus($(this));
            });
        });
    }

    function preventPageScroll(evt) {
        switch (evt.key) {
            case 'Down':
            case 'ArrowDown':
            case 'Up':
            case 'ArrowUp':
            case 'Spacebar':
            case ' ':
                evt.preventDefault();
        }
    }

    function deleteOptionFromMultiSelect(evt, defaultSelect, customSelect, options) {
        var optionDeleted = $(evt.target).parent().attr('value');

        // Remove chip from <ul>
        $(evt.target).parent().remove();

        // Reset option to be selected again in listbox menu and update default multi-select
        $.each(options, function (index, value) {
            if (optionDeleted === $(value).data('optionValue')) {
                $(this).removeClass('option-selected');
                $(this).attr('aria-selected', 'false');
                defaultSelect.children('option[value="' + $(this).data('optionValue') + '"]').prop('selected', false);
            }
        });

        // Set focus back to custom select
        customSelect.trigger('focus');
    }

    function setProgressBar() {
        var fieldsetIndex;

        $('fieldset').each(function () {
            if ($(this).css('display') === 'block') {
                var fieldset = $('form').find('fieldset');

                fieldsetIndex = fieldset.index(this);
            }
        });

        $('.progress-bar li').removeClass('active');
        $('.progress-bar li').each(function (index) {
            if (index <= fieldsetIndex) {
                $(this).addClass('active');
            }
        });
    }

    function showComboboxListbox(combobox) {
        combobox.addClass('listbox-active');
        combobox.attr('aria-expanded', 'true');
        combobox.children('.listbox').show();
    }
});
