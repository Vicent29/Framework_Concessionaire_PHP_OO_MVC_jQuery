function load_type_motor() {
    ajaxPromise('module/search/ctrl/ctrl_search.php?op=type_car', 'POST', 'JSON')
        .then(function(data) {
            $('#type_car').append('<option value = "0">Type Car</option>');
            for (row in data) {
                $('#type_car').append('<option value = "' + data[row].cod_tmotor + '">' + data[row].name_tmotor + '</option>');
            }
        }).catch(function() {
            console.log("Fail load type_car");
        });
}

function load_brand(data) {
    if (data == undefined) {
        ajaxPromise('module/search/ctrl/ctrl_search.php?op=brand_car', 'POST', 'JSON')
            .then(function(data) {
                $('#brand_car').empty();
                $('#brand_car').append('<option value = "0">Brands</option>');
                for (row in data) {
                    $('#brand_car').append('<option value = "' + data[row].name_brand + '">' + data[row].name_brand + '</option>');
                }
            }).catch(function(data) {
                console.log('Fail load brand');
            });
    } else {
        ajaxPromise('module/search/ctrl/ctrl_search.php?op=brand_category', 'POST', 'JSON', data)
            .then(function(data) {
                $('#brand_car').empty();
                $('#brand_car').append('<option value = "0">Brands</option>');
                for (row in data) {
                    $('#brand_car').append('<option value = "' + data[row].name_brand + '">' + data[row].name_brand + '</option>');
                }
            }).catch(function(data) {
                console.log('Fail load brand');
            });
    }

}

function launch_search() {
    load_type_motor();
    load_brand();
    $('#type_car').on('change', function() {
        let motor = $(this).val();
        if (motor === 0) {
            load_brand();
        } else {
            load_brand({ motor });
        }
    });
}

function autocomplete() {
    $("#autocom").on("keyup", function() {
        let sdata = { complete: $(this).val() };
        if (($('#type_car').val() != 0)) {
            sdata.type_car = $('#type_car').val();
            if (($('#type_car').val() != 0) && ($('#brand_car').val() != 0)) {
                sdata.brand_car = $('#brand_car').val();
            }
        }
        if (($('#type_car').val() == 0) && ($('#brand_car').val() != 0)) {
            sdata.brand_car = $('#brand_car').val();
        }
        ajaxPromise('module/search/ctrl/ctrl_search.php?op=autocomplete', 'POST', 'JSON', sdata)
            .then(function(data) {
                $('#search_auto').empty();
                $('#search_auto').fadeIn(10000000);
                for (row in data) {
                    $('<div></div>').appendTo('#search_auto').html(data[row].city).attr({ 'class': 'searchElement', 'id': data[row].city });
                }
                $(document).on('click', '.searchElement', function() {
                    $('#autocom').val(this.getAttribute('id'));
                    $('#search_auto').fadeOut(900);
                });
                $(document).on('click scroll', function(event) {
                    if (event.target.id !== 'autocom') {
                        $('#search_auto').fadeOut(1000);
                    }
                });
            }).catch(function() {
                $('#search_auto').fadeOut(500);
            });

    });

}

function btn_search() {
    $('#search-btn').on('click', function() {
        var search = [];

        if ($('#autocom').val() == "") {
            search.push({ "city": '0' });
            search.push({ "type_car": $('#type_car').val() });
            search.push({ "brand_car": $('#brand_car').val() });

        } else {
            search.push({ "city": $('#autocom').val() });
            search.push({ "type_car": $('#type_car').val() });
            search.push({ "brand_car": $('#brand_car').val() });

        }
        // console.log(search[0]['brand_car'][0]);
        localStorage.removeItem('filters');
        localStorage.removeItem('brand_filter');
        localStorage.removeItem('category_filter');
        localStorage.removeItem('type_motor_filter');
        localStorage.removeItem('order');

        localStorage.setItem('search', JSON.stringify(search));

        window.location.href = ' index.php?module=ctrl_shop&op=list ';

    });
}

$(document).ready(function() {
    launch_search();
    autocomplete();
    btn_search();
});