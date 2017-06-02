$(function() {
    function isValidDate(dateString)
    {
        // First check for the pattern
        if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
            return false;

        // Parse the date parts to integers
        var parts = dateString.split("/");
        var day = parseInt(parts[1], 10);
        var month = parseInt(parts[0], 10);
        var year = parseInt(parts[2], 10);

        // Check the ranges of month and year
        if(year < 2000 || year > 3000 || month == 0 || month > 12)
            return false;

        var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

        // Adjust for leap years
        if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
            monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    };

    function updatePreview(){
        var url  = '/admin/'+$('#main-form').attr('data-type');
            url += '/preview?';
        $("#main-form input").each(function(){
            var input = $(this);
            var value = input.val();
            if(input.attr('name') != '_token' && input.attr('type') != 'submit' && input.attr('name') != '_method') {
                value = value.replace(/\&/g, '{~and~}');
                var text = input.attr('name') + '=' + value + '&';
                url = url+text;
            }
        });

        console.log(url);
        $('.preview-obj').attr('data', url+'#toolbar=0&navpanes=0');
    }

    $("#datepicker").datepicker();

    $('input[name=date]').on('change', function() {
        var date = $(this).val();
        var defaultDate = $(this).attr('default');
        if(!isValidDate(date))
            $(this).val(defaultDate);

        updatePreview();
    });

    $('input[name=title], input[name=customer], input[name=company_line_1], input[name=company_line_2], input[name=po]').on('change', function() {
        var value = $(this).val();
        var defaultValue = $(this).attr('default');
        if(value == '')
            $(this).val(defaultValue);

        updatePreview();
    });

    $('input[name=material-included]').on('change', function() {
        if($(this).is(':checked'))
            $(this).val(true);
        else
            $(this).val(false);

        updatePreview();
    });

    $('.data-list').on('click', '.btn-edit', function() {
        var modal = $(this).attr('data-target');
        var wrapper = $(this).closest('.data-item');
        var target = wrapper.find('.update-target').attr('data-target');
        $(modal).find('input[name=action]').val('update');
        $(modal).find('input[name=action]').attr('data-target', target);

        $.each(wrapper.find('input'), function(element) {
            var input = $(this).attr('data-input');
            var value = $(this).attr('value');
            value = value.replace(/\<br>/g, '\n');
            $(input).val(value);
        });
    });

    $('.data-list').on('click', '.btn-delete', function() {
        $(this).closest('.data-item').remove();
        updatePreview();
    });

    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('input[name=action]').val('create');
        $(this).find('input[name=action]').attr('data-target', '');
        $(this).find('textarea').val('');
        $(this).find('input').val('');
        $(this).find('input[name=action]').val('create');
    });

    $('.modal form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var type = $(this).attr('form-type');
        var action = form.find('input[name=action]').val();
        var target = form.find('input[name=action]').attr('data-target');
        var text = form.find('textarea').val();
        var quantity = form.find('input[name=quantity]').val();
        var unit = form.find('input[name=unit]').val();
        var price = form.find('input[name=price]').val();
        var count = $('.'+type+'-item').length;

        // validation
        if(text.replace(/\s/g, "").length === 0) {
            form.find('textarea]').val('');
            return false;
        }

        if(type == 'service') {
            text = text.replace(/(?:\r\n|\r|\n)/g, '<br>');
            var html  = '<div class="service-item data-item" id="service-'+count+'">';
                html +=     '<p class="service main-data">';
                html +=         text;
                html +=     '</p>';
                html +=     '<div>';
                html +=         '<span class="price">';
                html +=             'RM'+price;
                html +=         '</span>';
                html +=         '<a class="btn-edit" data-toggle="modal" data-target="#serviceModal"><i class="fa fa-pencil"></i></a>';
                html +=         '<a class="btn-delete"><i class="fa fa-trash"></i></a>';
                html +=     '</div>';
        }
        else if(type == 'material') {
            var html  = '<div class="material-item data-item" id="material-'+count+'">';
                html +=     '<p class="main-data">';
                html +=         '<span class="material">'+text+'</span> ';
                html +=         '<span class="quantity">'+quantity+'</span> ';
                html +=         '<span class="unit">'+unit+'</span> = ';
                html +=         '<span class="price">RM' +(price * quantity)+'</span>';
                html +=         '<a class="btn-edit" data-toggle="modal" data-target="#materialModal"><i class="fa fa-pencil"></i></a>';
                html +=         '<a class="btn-delete"><i class="fa fa-trash"></i></a>';
                html +=     '</p>';
                // input
                html +=     '<input type="hidden" class="inputQuantity" name="material['+count+'][quantity]" value="'+quantity+'" data-input="input[name=quantity]">';
                html +=     '<input type="hidden" class="inputMaterial" name="material['+count+'][unit]" value="'+unit+'" data-input="input[name=unit]">';
        }
        html +=     '<input type="hidden" class="inputText" name="'+type+'['+count+'][text]" value="'+text+'" data-input="textarea[name='+type+']">';
        html +=     '<input type="hidden" class="inputPrice" name="'+type+'['+count+'][price]" value="'+price+'" data-input="input[name=price]">';
        html +=     '<span class="update-target" data-target="#'+type+'-'+count+'"></span>';
        html += '</div>';

        if(action == 'create') {
            $('.'+type+'-list').append(html);
            form.find('textarea').val('');
            form.find('input').val('');
            form.find('input[name=action]').val('create');
            form.find('textarea').focus();
        }
        else {
            $(target).find('.'+type).html(text);
            $(target).find('.inputText').val(text);
            $(target).find('.price').html('RM'+price);
            $(target).find('.inputPrice').val(price);

            if(type == "material") {
                $(target).find('.price').html('RM'+(price * quantity));
                $(target).find('.quantity').html(quantity);
                $(target).find('.inputQuantity').val(quantity);
                $(target).find('.unit').html(unit);
                $(target).find('.inputUnit').val(unit);

            }

            $('#'+type+'Modal').modal('toggle');
        }
        updatePreview();
    });

    $('.btn-save').on('click', function(){
        $($(this).attr('data-form')).submit();
    });

    $('.btn-delete').on('click', function(){
        var form = $(this).attr('data-target');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this quotation and related invoice!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel pls!",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm){
            if(isConfirm){$(form).submit();}
        });
    });

    $('#quotation-table').DataTable({
        "paging":    false,
        "info":      false,
        "aaSorting": [],
        columnDefs: [
            {
                "targets": [6],
                "orderable": false,
            }
        ],
    });

    $('#invoice-table').DataTable({
        "paging":    false,
        "info":      false,
        "aaSorting": [],
        columnDefs: [
            {
                "targets": [5],
                "orderable": false,
            }
        ],
    });
});
