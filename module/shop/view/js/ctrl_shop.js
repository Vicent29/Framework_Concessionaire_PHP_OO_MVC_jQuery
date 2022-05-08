function ajaxForSearch(durl, args) {
    var total_prod = args[0];
    if (total_prod != 0) {
        localStorage.setItem('total_prod', total_prod);
    } else {
        if (localStorage.getItem('total_prod')) {
            total_prod = localStorage.getItem('total_prod');
        } else {
            total_prod = 0;
        }
    }
    ajaxPromise(durl, 'POST', 'JSON', {'args': args})
        .then(function(data) {
            $('#content_shop_cars').empty();
            $('.date_car' && '.date_img').empty();
            $('#div_map_details').hide();

            //Mejora para que cuando no hayan resultados en los filtros aplicados
            if (data.length == "0") {
                $('<div></div>').appendTo('#content_shop_cars')
                    .html(
                        '<h3>¡No se encuentarn resultados con los filtros aplicados!</h3>'
                    )
            } else {
                load_map_shop();
                for (row in data) {

                    $('<div></div>').attr({ 'id': data[row].id_car, 'class': 'list_content_shop' }).appendTo('#content_shop_cars')
                        .html(
                            "<div class='list_product'>" +
                            "<div class='img-container'>" +
                            "<img src= '" + data[row].img_car + "'" + "</img>" +
                            "</div>" +
                            "<div class='product-info'>" +
                            "<div class='product-content'>" +
                            "<h1><b>" + data[row].id_brand + " " + data[row].name_model + "<a class='list__heart' id='" + data[row].id_car + "'><i id= " + data[row].id_car + " class='fa-solid fa-heart fa-lg'></i></a>" + "</b></h1>" +
                            "<p>Up-to-date maintenance and revisions</p>" +
                            "<ul>" +
                            "<li> <i id='col-ico' class='fa-solid fa-road fa-xl'></i>&nbsp;&nbsp;" + data[row].Km + " KM" + "</li>" +
                            "<li> <i id='col-ico' class='fa-solid fa-person fa-xl'></i>&nbsp;&nbsp;&nbsp;" + data[row].gear_shift + "</li>" +
                            "<li> <i id='col-ico' class='fa-solid fa-palette fa-xl'></i>&nbsp;" + data[row].color + "</li>" +
                            "</ul>" +
                            "<div class='buttons'>" +
                            "<button id='" + data[row].id_car + "' class='more_info_list button add' >More Info</button>" +
                            "<button class='button buy' >Buy</button>" +
                            "<span class='button' id='price'>" + data[row].price + '€' + "</span>" +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</div>"
                        )
                    addMarker_map(data[row], "list");
                }
                load_likes_user();
                //Mejora para que redireccione al div del coche en el que e has dado like antes de estar registrado en el shop.
                if (localStorage.getItem('id_car')) {
                    var id = "#" + localStorage.getItem('id_car');
                    $("html, body").animate({ scrollTop: $(id).offset().top }, 1000);
                }
            }

        }).catch(function() {
            console.log("Errror Function ajxForSearch SHOP");
            // window.location.href = "index.php?module=ctrl_exceptions&op=503&type=503&lugar=Function ajxForSearch SHOP";
        });
}

function loadCars(total_prod = 0, items_page = 4) {

    var verificate_filters = localStorage.getItem('filters') || false;
    var brand_filter = localStorage.getItem('brand_filter') || false;
    var category_filter = localStorage.getItem('category_filter') || false;
    var motor_filter = localStorage.getItem('type_motor_filter') || false;
    var verificate_search = localStorage.getItem('search') || false;
    var verificate_orderBy = localStorage.getItem('order') || false;
    var redirect_like = localStorage.getItem('redirect_like') || false;

    if (verificate_filters != false) {
        shop_filters(total_prod, items_page);
        highlightFilters();
    } else if (brand_filter != false) {
        load_brand_filter(total_prod, items_page);
    } else if (category_filter != false) {
        load_category_filter(total_prod, items_page);
    } else if (motor_filter != false) {
        load_motor_filter(total_prod, items_page);
    } else if (verificate_search != false) {
        load_search(total_prod, items_page);
    } else if (verificate_orderBy != false) {
        load_orderby(total_prod, items_page);
        highlightOrderBy();
    } else if (redirect_like != false) {
        redirect_login_like();
    } else {
        const atributos= [total_prod, items_page];
        ajaxForSearch('?module=shop&op=all_cars', atributos);
    }
}

function clicks() {
    $(document).on("click", ".more_info_list", function() {
        var id_car = this.getAttribute('id');
        loadDetails(id_car);
    });
    $(document).on("click", ".list__heart", function() {
        var id_car = this.getAttribute('id');
        click_like(id_car, "list_all");
    });

    $(document).on("click", ".details__heart", function() {
        var id_car = this.getAttribute('id');
        click_like(id_car, "details");
    });
}

function loadDetails(id_car) {

    ajaxPromise('?module=shop&op=details_car', 'POST', 'JSON', {'id': id_car})
    
    .then(function(data) {

        $('#content_shop_cars').empty();
        $('.date_img_dentro').empty();
        $('.date_car_dentro').empty();
        $('#div_filters').hide();
        $('#div_map_shop').hide();
        $('#div_map_details').show();
        $('#pagination').empty();
        $('.results').empty();

        for (row in data[1][0]) {

            $('<div></div>').attr({ 'id': data[1][0].id_img, class: 'date_img_dentro' }).appendTo('.date_img')
                .html(
                    "<div class='content-img-details'>" +
                    "<img src= '" + data[1][0][row].img_cars + "'" + "</img>" +
                    "</div>"
                )

        }

        $('<div></div>').attr({ 'id': data[0][0].id_car, class: 'date_car_dentro' }).appendTo('.date_car')
            .html(
                "<div class='list_product_details'>" +
                "<div class='product-info_details'>" +
                "<div class='product-content_details'>" +
                "<h1><b>" + data[0][0].id_brand + " " + data[0][0].name_model + "</b></h1>" +
                "<hr class=hr-shop>" +
                "<table id='table-shop'> <tr>" +
                "<td> <i id='col-ico' class='fa-solid fa-road fa-2xl'></i> &nbsp;" + data[0][0].Km + "KM" + "</td>" +
                "<td> <i id='col-ico' class='fa-solid fa-person fa-2xl'></i> &nbsp;" + data[0][0].gear_shift + "</td>  </tr>" +
                "<td> <i id='col-ico' class='fa-solid fa-car fa-2xl'></i> &nbsp;" + data[0][0].name_cat + "</td>" +
                "<td> <i id='col-ico' class='fa-solid fa-door-open fa-2xl'></i> &nbsp;" + data[0][0].num_doors + "</td>  </tr>" +
                "<td> <i id='col-ico' class='fa-solid fa-gas-pump fa-2xl'></i> &nbsp;" + data[0][0].name_tmotor + "</td>" +
                "<td> <i id='col-ico' class='fa-solid fa-calendar-days fa-2xl'></i> &nbsp;" + data[0][0].matricualtion_date + "</td>  </tr>" +
                "<td> <i id='col-ico' class='fa-solid fa-palette fa-2xl'></i> &nbsp;" + data[0][0].color + "</td>" +
                "<td> <i class='fa-solid fa-location-dot fa-2xl'></i> &nbsp;" + data[0][0].city + "</td> </tr>" +
                "</table>" +
                "<hr class=hr-shop>" +
                "<h3><b>" + "More Information:" + "</b></h3>" +
                "<p>This vehicle has a 2-year warranty and reviews during the first 6 months from its acquisition.</p>" +
                "<div class='buttons_details'>" +
                "<a class='button add' href='#'>Add to Cart</a>" +
                "<a class='button buy' href='#'>Buy</a>" +
                "<span class='button' id='price_details'>" + data[0][0].price + "<i class='fa-solid fa-euro-sign'></i> </span>" +
                "<a class='details__heart' id='" + data[0][0].id_car + "'><i id=" + data[0][0].id_car + " class='fa-solid fa-heart fa-lg'></i></a>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>"
            )
        $('html, body').animate({ scrollTop: $(".date_car") });
        load_map_details(data[0][0]);
        addMarker_map(data[0][0], "details");
        more_cars_related(data[0][0].motor);
        load_likes_user();


        $('.date_img').slick({
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            autoplay: true,
            autoplaySpeed: 1500
        });


    }).catch(function() {
        // window.location.href = "index.php?module=ctrl_exceptions&op=503&type=503&lugar=Load_Details SHOP";
    });


    ajaxPromise('?module=shop&op=count_more_visit', 'POST', 'JSON', { 'id_car': id_car })
        .then(function(data) {}).catch(function() {});

}

function load_filter() {

    $('<div></div>').attr({ 'id': 'filters', class: 'filters' }).appendTo('.filters_content')
        .html(
            '<div class="filters1">' +
            '<p><u>SEARCH CAR:</u></p>' +
            '<hr class=hr-filter>' +
            '<div class="color">' +
            '<h4>COLOR:</h4>' +
            '<input type="checkbox" value="White" id="White" class="color">White</br>' +
            '<input type="checkbox" value="Blue" id="Blue" class="color">Blue</br>' +
            '<input type="checkbox" value="Black" id="Black" class="color">Black</br>' +
            '<input type="checkbox" value="Red" id="Red" class="color">Red</br>' +
            '<input type="checkbox" value="Grey" id="Grey" class="color">Grey</br>' +
            '<input type="checkbox" value="Orange" id="Orange" class="color">Orange</br>' +
            '<input type="checkbox" value="Brown" id="Brown" class="color">Brown</br>' +
            '</div>' +
            '<hr class=hr-filter>' +
            '<div class="doors">' +
            '<h4>NUMBER DOORS:</h4>' +
            '<input type="radio" name="doors" value="3" id="3" class="doors">3</br>' +
            '<input type="radio" name="doors" value="5" id="5" class="doors">5</br>' +
            '</div>' +
            '<hr class=hr-filter>' +
            '<div class="doors">' +
            '<h4>CATEGORY:</h4>' +
            '<select name="select_cat" id="select_cat">' +
            '<option value="*" id="*">All</ option>' +
            '<option value="1" id="1">Km 0</option>' +
            '<option value="2" id="2">Second Hand</option>' +
            '<option value="3" id="3">Renting</option>' +
            '<option value="4" id="4">Pre-Owned</option>' +
            '<option value="5" id="5">Offer</option>' +
            '<option value="6" id="6">New</option>' +
            '</select>' +
            '</div>' +
            '<hr class=hr-filter>' +
            '</br><input type="button" class="submit_filter" id="buttons_filters" value="SEARCH">' +
            '<input type="button" class="remove_filters" id="buttons_filters" value="REMOVE"></br>' +
            '</div>' +

            '<div class="orderby_content">' +
            '<p>ORDER BY:</p>' +
            '<select id="orderby">' +
            '<option value = "0">Order by...</option>' +
            '<option value = "price">Price</option>' +
            '<option value = "km">KM</option>' +
            '</select>' +
            '<input type="button" value="ORDER" id="order-btn" class="order-btn"/>' +
            '</div>'
        )

    $(document).on('click', '.submit_filter', function() {
        console.log("Aplicar filtro");
        save_filters();
        load_pagination();
    });

    //remove all filters
    $(document).on('click', '.remove_filters', function() {
        remove_filters();

    });


}

function save_filters() {
    var color = [];
    var doors = [];
    var category = [];
    var filters = [];

    localStorage.removeItem('filters');
    localStorage.removeItem('search');
    localStorage.removeItem('order');
    //color
    $.each($("input[class='color']:checked"), function() {
        color.push($(this).val());
    });
    if (color.length != 0) {
        filters.push({ "Color": color });
    } else {
        filters.push({ "Color": '*' });
    }
    //doors
    $.each($("input[class='doors']:checked"), function() {
        doors.push($(this).val());
    });

    if (doors.length != 0) {
        filters.push({ "Num_doors": doors });
    } else {
        filters.push({ "Num_doors": '*' });
    }

    //category
    var cat = document.getElementById("select_cat").value;
    if (cat != 0) {
        category.push(cat);
        if (category == "*") {
            filters.push({ "category": "*" });
        } else {
            filters.push({ "category": category });
        }
    } else {
        filters.push({ "category": '*' });
    }
    //all_filters (localstorage)
    if (filters.length != 0) {
        localStorage.setItem('filters', JSON.stringify(filters));
    }
    shop_filters();
}

function shop_filters(total_prod = 0, items_page = 4) {
    localStorage.removeItem('page');
    var all_filters = JSON.parse(localStorage.getItem('filters'));
    var color = all_filters[0].Color;
    var doors = all_filters[1].Num_doors[0];
    var category = all_filters[2].category[0];
    var opc_sql = "select";
    if (color == "*" && doors == "*" && category == "*"){ //Mejora cuando quite todos los fiiltros y le de a buscar
        remove_filters();
    }else {
    const atributos= [total_prod, items_page, color, doors, category, opc_sql];
    ajaxForSearch('?module=shop&op=operations_filters_shop', atributos);
    }
}

function highlightFilters() {
    var all_filters = JSON.parse(localStorage.getItem('filters'));

    if (all_filters[1].Num_doors[0] != '*') {
        document.getElementById(all_filters[1].Num_doors[0]).setAttribute('checked', true);
    }
    if (all_filters[2].category[0] != '*') {
        document.getElementById('select_cat').value = all_filters[2].category[0];
    }
    if (all_filters[0].Color[0] != '*') {
        for (row in all_filters[0].Color) {
            document.getElementById(all_filters[0].Color[row]).setAttribute('checked', true);
        }
    }
}

function highlightOrderBy() {
    var orderby = JSON.parse(localStorage.getItem('order'));

    if (orderby[0].order) {
        document.getElementById('orderby').value = orderby[0].order;
    }
}

function remove_filters() {
    localStorage.removeItem('filters');
    localStorage.removeItem('brand_filter');
    localStorage.removeItem('category_filter');
    localStorage.removeItem('type_motor_filter');
    localStorage.removeItem('search');
    localStorage.removeItem('order');
    location.reload();

}

function load_map_shop() {

    var position = { lat: 40.416705, lng: -3.7035825 };
    mapboxgl.accessToken = 'pk.eyJ1IjoidmljZW50MjkiLCJhIjoiY2t6eWhiOXFmMDBkbzNqcGI3dzV2Y2pkYSJ9.Ryh_RUFmGLZV-VNy8Ompkw';
    map = new mapboxgl.Map({
        container: 'div_map_shop', // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: position, // starting position [lng, lat]
        zoom: 5 // starting zoom
    });
    map.addControl(new mapboxgl.NavigationControl());
    map.addControl(new mapboxgl.FullscreenControl());

}

function load_map_details(data) {

    //Mejora para que el popup del details este centrado en la pantalla y el popup se vea dentro del mapa sin tener que moverse.
    var lat = (data.lat - 0.10);
    var position = [data.lon, lat];
    mapboxgl.accessToken = 'pk.eyJ1IjoidmljZW50MjkiLCJhIjoiY2t6eWhiOXFmMDBkbzNqcGI3dzV2Y2pkYSJ9.Ryh_RUFmGLZV-VNy8Ompkw';
    map = new mapboxgl.Map({
        container: 'div_map_details', // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: position, // starting position [lng, lat]
        zoom: 9 // starting zoom
    });
    map.addControl(new mapboxgl.NavigationControl());
    map.addControl(new mapboxgl.FullscreenControl());

}

function addMarker_map(data, opc) {

    if (opc == "list") {

        popup = new mapboxgl.Popup({ offset: 25 }).setHTML(
            "<div class='more_info_popup'>" +
            "<img src='" + data.img_car + "'></img>" +
            "<h4><b>" + data.id_brand + " " + data.name_model + "</b></h4>" +
            "<table id='table-shop'> <tr>" +
            "<td> <i class='fa-solid fa-location-dot fa-xl'></i> &nbsp;" + data.city + "</td>" +
            "<td> <i id='col-ico' class='fa-solid fa-road fa-xl'></i> &nbsp;" + data.Km + " KM" + "</td>  </tr>" +
            "<td> <i id='col-ico' class='fa-solid fa-palette fa-xl'></i> &nbsp;" + data.color + "</td>" +
            "<td ><i class='fa-solid fa-coins fa-xl'></i> &nbsp;" + data.price + " €" + "</td></tr>" +
            "</table>" +
            "<button class='more_info_list' id='" + data.id_car + "'>MORE INFO</button>" +
            "<div/>"
        );
    } else if (opc == "details") {

        popup = new mapboxgl.Popup({ offset: 25 }).setHTML(
            "<img src='" + data.img_car + "'></img>" +
            "<h4><b>" + data.id_brand + " " + data.name_model + "</b></h4>" +
            "<table id='table-shop'> <tr>" +
            "<td> <i class='fa-solid fa-location-dot fa-xl'></i> &nbsp;" + data.city + "</td>" +
            "<td> <i id='col-ico' class='fa-solid fa-road fa-xl'></i> &nbsp;" + data.Km + " KM" + "</td>  </tr>" +
            "<td> <i id='col-ico' class='fa-solid fa-palette fa-xl'></i> &nbsp;" + data.color + "</td>" +
            "<td ><i class='fa-solid fa-coins fa-xl'></i> &nbsp;" + data.price + " €" + "</td></tr>" +
            "</table>"

        );
    }
    marker = new mapboxgl.Marker()
        .setPopup(popup)
        .setLngLat([data.lon, data.lat])
        .addTo(map);
}

function load_mapbox_variables() {
    const map = null;
    const marker = null;
    const popup = null;
}

function load_brand_filter(total_prod = 0, items_page) {
    localStorage.removeItem('page');
    var opc= "brand";
    var array_brand = JSON.parse(localStorage.getItem('brand_filter'));
    var brand = array_brand[0].name_brand[0];
    const atributos= [total_prod, items_page, opc, brand];

    ajaxForSearch('?module=shop&op=home_filter', atributos);
}

function load_category_filter(total_prod = 0, items_page) {
    localStorage.removeItem('page');
    var opc= "cate";
    var array_category = JSON.parse(localStorage.getItem('category_filter'));
    var category = array_category[0].category_home[0];
    const atributos= [total_prod, items_page, opc, category];

    ajaxForSearch('?module=shop&op=home_filter',atributos);
}

function load_motor_filter(total_prod = 0, items_page) {
    localStorage.removeItem('page');
    var opc= "tmotor";
    var array_tmotor = JSON.parse(localStorage.getItem('type_motor_filter'));
    var motor = array_tmotor[0].name_tmotor[0];
    const atributos= [total_prod, items_page, opc, motor];
    ajaxForSearch('?module=shop&op=home_filter', atributos);
}

function load_search(total_prod = 0, items_page) {
    localStorage.removeItem('page');
    var search = JSON.parse(localStorage.getItem('search'));
    var type_car = search[1]['type_car'];
    var brand_car = search[2]['brand_car'];
    var city = search[0]['city'];
    var opc_sql = "select";
    const atributos= [total_prod, items_page, type_car, brand_car, city, opc_sql];
    ajaxForSearch('?module=shop&op=operations_search_filter', atributos);
}

function save_orderby() {
    $(document).on('click', '.order-btn', function() {

        var orderby = [];

        if ($('#orderby').val() == 0) {
            orderby.push({ "order": '0' });

        } else {
            orderby.push({ "order": $('#orderby').val() });
        }

        localStorage.removeItem('filters');
        localStorage.removeItem('brand_filter');
        localStorage.removeItem('category_filter');
        localStorage.removeItem('type_motor_filter');
        localStorage.removeItem('search');

        localStorage.setItem('order', JSON.stringify(orderby));
        window.location.href = '?module=shop&op=list ';
    });
}

function load_orderby(total_prod = 0, items_page = 4) {
    var all_orderby = JSON.parse(localStorage.getItem('order'));
    var one_orderby = all_orderby[0].order;
    const atributos= [total_prod, items_page,one_orderby];

    ajaxForSearch('?module=shop&op=order_filter', atributos);
}

function load_pagination() {

    if (localStorage.getItem('filters')) {
        var all_filters = JSON.parse(localStorage.getItem('filters'));
        var color = all_filters[0].Color;
        var doors = all_filters[1].Num_doors[0];
        var category = all_filters[2].category[0];
        var opc_sql= "count";
        var url = '?module=shop&op=operations_filters_shop';
        var total_prod= "0";
        var items_page= "4";
        var args = [total_prod, items_page,color, doors, category, opc_sql];

    } else if (localStorage.getItem('brand_filter')) {
        var opc_filter = "brand";
        var array_brand = JSON.parse(localStorage.getItem('brand_filter'));
        var brand = array_brand[0].name_brand[0];
        var url = "?module=shop&op=count_cars_home_filters";
        var args = [opc_filter, brand];

    } else if (localStorage.getItem('category_filter')) {
        var opc_filter= "cate";
        var array_category = JSON.parse(localStorage.getItem('category_filter'));
        var category = array_category[0].category_home[0];
        var url = "?module=shop&op=count_cars_home_filters";
        var args = [opc_filter, category];

    } else if (localStorage.getItem('type_motor_filter')) {
        var opc_filter= "tmotor";
        var array_tmotor = JSON.parse(localStorage.getItem('type_motor_filter'));
        var motor = array_tmotor[0].name_tmotor[0];
        var url = "?module=shop&op=count_cars_home_filters";
        var args = [opc_filter, motor];

    } else if (localStorage.getItem('search')) {
        var search = JSON.parse(localStorage.getItem('search'));
        var type_car = search[1]['type_car'];
        var brand_car = search[2]['brand_car'];
        var city = search[0]['city'];
        var opc_sql = "count";
        var total_prod= "0";
        var items_page= "4";
        var args= [total_prod, items_page, type_car, brand_car, city, opc_sql];
        var url = "?module=shop&op=operations_search_filter";

    } else if (localStorage.getItem('order')) {
        var value_orderby = JSON.parse(localStorage.getItem('order'));
        var url = '?module=shop&op=count_order_filter';
        var args = { 'value_orderby': value_orderby }
        
    } else {
        var url = "?module=shop&op=count_cars_pag";
    }
    ajaxPromise(url, 'POST', 'JSON', {args})
        .then(function(data) {
            var total_pages = 0;
            var total_prod = data[0].n_prod;

            if (total_prod >= 4) {
                total_pages = Math.ceil(total_prod / 4);
            } else {
                total_pages = 1;
            }

            $('#pagination').bootpag({
                total: total_pages,
                page: localStorage.getItem('page') ? localStorage.getItem('page') : 1,
                maxVisible: total_pages
            }).on('page', function(event, num) {
                localStorage.setItem('page', num);
                localStorage.removeItem('id_car');
                total_prod = 4 * (num - 1);
                if (total_prod == 0) {
                    localStorage.setItem('total_prod', 0)
                }
                loadCars(total_prod, 4);
                $('html, body').animate({ scrollTop: $(".list__content") });
            });
        }).catch(function() {
            console.log('Fail pagination');
        });
}

function cars_related(loadeds = 0, type_car, total_items) {
    let items = 3;
    let loaded = loadeds;
    let type = type_car;
    let total_item = total_items;

    ajaxPromise("?module=shop&op=cars_related", 'POST', 'JSON', { 'type': type, 'loaded': loaded, 'items': items })
        .then(function(data) {
            if (loaded == 0) {
                console.log(data);
                $('<div></div>').attr({ 'id': 'title_content', class: 'title_content' }).appendTo('.results')
                    .html(
                        '<h2 class="cat">Cars related</h2>'
                    )
                for (row in data) {
                    if (data[row].id_car != undefined) {
                        $('<div></div>').attr({ 'id': data[row].id_car, 'class': 'more_info_list' }).appendTo('.title_content')
                            .html(
                                "<li class='portfolio-item'>" +
                                "<div class='item-main'>" +
                                "<div class='portfolio-image'>" +
                                "<img src = " + data[row].img_car + " alt='imagen car' </img> " +
                                "</div>" +
                                "<h5>" + data[row].id_brand + "  " + data[row].name_model + "</h5>" +
                                "</div>" +
                                "</li>"

                            )
                    }
                }
                $('<div></div>').attr({ 'id': 'more_car__button', 'class': 'more_car__button' }).appendTo('.title_content')
                    .html(
                        '<button class="load_more_button" id="load_more_button">LOAD MORE</button>'
                    )
            }
            if (loaded >= 3) {
                for (row in data) {
                    if (data[row].id_car != undefined) {
                        console.log(data);
                        $('<div></div>').attr({ 'id': data[row].id_car, 'class': 'more_info_list' }).appendTo('.title_content')
                            .html(
                                "<li class='portfolio-item'>" +
                                "<div class='item-main'>" +
                                "<div class='portfolio-image'>" +
                                "<img src = " + data[row].img_car + " alt='imagen car' </img> " +
                                "</div>" +
                                "<h5>" + data[row].id_brand + "  " + data[row].name_model + "</h5>" +
                                "</div>" +
                                "</li>"

                            )
                    }
                }
                var total_cars = total_item - 3;
                if (total_cars <= loaded) {
                    $('.more_car__button').empty();
                    $('<div></div>').attr({ 'id': 'more_car__button', 'class': 'more_car__button' }).appendTo('.title_content')
                        .html(
                            "</br><button class='btn-notexist' id='btn-notexist'></button>"
                        )
                } else {
                    $('.more_car__button').empty();
                    $('<div></div>').attr({ 'id': 'more_car__button', 'class': 'more_car__button' }).appendTo('.title_content')
                        .html(
                            '<button class="load_more_button" id="load_more_button">LOAD MORE</button>'
                        )
                }
            }
        }).catch(function() {
            console.log("error cars_related");
        });
}

function more_cars_related(type_car) {
    var type_car = type_car;
    var items = 0;
    ajaxPromise('?module=shop&op=count_cars_related', 'POST', 'JSON', { 'type_car': type_car })
        .then(function(data) {
            var total_items = data[0].n_prod;
            cars_related(0, type_car, total_items);
            $(document).on("click", '.load_more_button', function() {
                items = items + 3;
                $('.more_car__button').empty();
                cars_related(items, type_car, total_items);
            });
        }).catch(function() {
            console.log('error total_items');
        });
}

function click_like(id_car, lugar) {
    var token = localStorage.getItem('token');
    if (token) {
        ajaxPromise("?module=shop&op=control_likes", 'POST', 'JSON', { 'id_car': id_car, 'token': token })
            .then(function(data) {
                // console.log("RESPUESTAA");
                // console.log(data);
                $("#" + id_car + ".fa-heart").toggleClass('like_red');
            }).catch(function() {
                console.log("Error Function click_like SHOP");
                // window.location.href = "index.php?module=ctrl_exceptions&op=503&type=503&lugar=Function click_like SHOP";
            });

    } else {
        const redirect = [];
        redirect.push(id_car, lugar);
        console.log(redirect);
        localStorage.setItem('redirect_like', redirect);
        localStorage.setItem('id_car', id_car);

        toastr.warning("Debes de iniciar session");
        setTimeout("location.href = '?module=login&op=login_register_view';", 1000);
    }
}

function load_likes_user() {
    var token = localStorage.getItem('token');
    if (token) {
        ajaxPromise("?module=shop&op=load_likes_user", 'POST', 'JSON', { 'token': token })
            .then(function(data) {
                for (row in data) {
                    $("#" + data[row].id_car + ".fa-heart").toggleClass('like_red');
                }
            }).catch(function() {
                console.log("Error Function load_like_user SHOP");
                // window.location.href = "index.php?module=ctrl_exceptions&op=503&type=503&lugar=Function load_like_user SHOP";
            });
    }
}

function redirect_login_like() {
    var redirect = localStorage.getItem('redirect_like').split(",");
    if (redirect[1] == "details") {
        loadDetails(redirect[0]);
        localStorage.removeItem('redirect_like');
        localStorage.removeItem('page');
    } else if (redirect[1] == "list_all") {
        localStorage.removeItem('redirect_like');
        loadCars();
    }
}

$(document).ready(function() {
    load_mapbox_variables();
    load_filter();
    loadCars();
    clicks();
    save_orderby();
    load_pagination();
});