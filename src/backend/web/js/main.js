// listen click, open modal and .load content
$(document).on('click', '.modalForm', function (e){

    let modal = $('#modal');
    let modalContent = $("#modalContent");
    let disabledBtn = $('.modalForm.disabled');

    if($(this).hasClass('loading')) {
        return false;
    }

    $(this).addClass('disabled');

    $.get($(this).attr('href'))
        .done(html => {
            modalContent.html(html);
            modal.find("form").on('beforeSubmit', submitForm).on('submit', el => el.preventDefault());
            modal.modal('show');
            if($('.bootstrap-switch-id-langSwitch').length) {
                toggleI18N();
            }
        })
        .fail(function(){
            toastr["error"]("Error while processing request");
            disabledBtn.removeClass('disabled');
            refreshPjax();
        });

    e.preventDefault();
});

// on close modal
$(document).on('hidden.bs.modal', '#modal', function (e) {

    let modalId = $('#modal').find('form').attr('id');

    refreshPjax(modalId);

    let body = $('body');

    body.addClass('modal-open-close');

    $('.modalForm.disabled').removeClass('disabled');

    if ($(e.target).find('a.save-changes').length > 0) {
        body.addClass('modal-open');
        return true;
    }

    $(this).find(".modal-header").find("h4").remove();
    $(this).find(".modal-content").find("form").replaceWith('<div class="modal-body"></div>');
});

// serialize form, render response and close modal
submitForm = () => {
    const form = $('#modal').find("form");
    $.post(
        form.attr("action"), // serialize Yii2 form
        form.serialize()
    )
        .done(result => {
            try {
                result = $.parseJSON(result);
            } catch (e) {
                $('#modal').modal('hide');
            }

            if(result.status === 'error') {
                toastr["error"](result.message);
            } else {
                $('#modal').modal('hide');
            }
        })
        .fail(() => {
            console.log("server error");
            toastr["error"]("Error while processing request");
        });
    return false;
};

$(function () {
    let toggle = $('[data-toggle="tooltip"]');

    toggle.tooltip({trigger: 'manual'});

    $('body').on('click', '[data-toggle="tooltip"]', () => {
        toggle.not($(this)).tooltip('hide');

        if ($(this).parent().find('.tooltip').length)
            toggle.tooltip('hide');
        else
            $(this).tooltip('show');
    });
});

refreshPjax = (modalId = null) => {
    if(modalId) {
        // Update Pjax container for catalog forms
        if(modalId === 'category-form' && $('#category-pjax').length) {
            $.pjax.reload({container: '#category-pjax', async: false });
        }

        if(modalId === 'subcategory-form' && $('#subcategory-pjax').length) {
            $.pjax.reload({container: '#subcategory-pjax', async: false });
        }
    }

    if ($("#refresh").length) {
        $.pjax.reload({container: '#refresh', async: false });
    }

    if ($("#refreshModal").length) {
        $.pjax.reload({container: '#refreshModal', async: false });
    }
};

$(document).on('pjax:success', () => {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'manual'
    });
});

toggleI18N = () => {
    if ($('#langSwitch').bootstrapSwitch('state') + 0) {
        $('.en').fadeOut('fast', () => $('.ru').fadeIn('fast'));
    } else {
        $('.ru').fadeOut('fast', () => $('.en').fadeIn('fast'));
    }
};
