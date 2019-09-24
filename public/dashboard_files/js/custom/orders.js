$(document).ready(function () {

    // when click on add button add the product to the ordered list
    $('.add-product-btn').on('click', function (e) {

        e.preventDefault();

        var name = $(this).data('name');
        var id = $(this).data('id');
        var price = $.number($(this).data('price'), 2);

        $(this).removeClass('btn-success').addClass('btn-default disabled');

        var html =
            `<tr>
                <td> ${name} </td>
                <td> <input type="number" name="products[${id}][quantity]" data-price="${price}" class="form-control input-sm product-quantity" min="1" value="1"> </td>
                <td class="product-price" > ${price} </td>
                <td> <button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}"><span class="fa fa-trash"></span></button> </td>
            </tr>`;

        $('.order-list').append(html);

        calculateTotal();
    });

    $('body').on('keyup change', '.product-quantity', function () {

        var quantity = Number($(this).val());
        var unitPrice = parseFloat($(this).data('price').replace(/,/g, ''));
        $(this).closest('tr').find('.product-price').html($.number(quantity * unitPrice, 2));

        calculateTotal()
    });

    //when click on the disabled button
    $('body').on('click', '.disabled', function (e) {
       e.preventDefault();
    });

    //when click on the remove button make the same disabled and  remove all the table row
    $('body').on('click', '.remove-product-btn', function (e) {
       e.preventDefault();
       var id = $(this).data('id');

       $('#product-' + id).removeClass('btn-default disabled').addClass('btn-success');
       $(this).closest('tr').remove();
    });

    // show the order using ajax request
    $('.order-products').on('click', function (e) {
        e.preventDefault();
        $('#loading').css('display', 'flex');

        var url = $(this).data('url');
        var method = $(this).data('method');

        $.ajax({
           url: url,
            method: method,
            success: function (data) {
                $('#loading').css('display', 'none');
                $('#order-product-list').empty();
                $('#order-product-list').append(data);
            }
        });

    });// end of function

    //print the order for the bill
    $(document).on('click', '.print-btn', function (e) {
        e.preventDefault();
        $('#print-area').printThis({
            header: "<h2> Client Order </h2>",
        });

        // $("#mySelector").printThis({
        //     debug: false,               // show the iframe for debugging
        //     importCSS: true,            // import parent page css
        //     importStyle: false,         // import style tags
        //     printContainer: true,       // print outer container/$.selector
        //     loadCSS: "",                // path to additional css file - use an array [] for multiple
        //     pageTitle: "",              // add title to print page
        //     removeInline: false,        // remove inline styles from print elements
        //     removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
        //     printDelay: 333,            // variable print delay
        //     header: null,               // prefix to html
        //     footer: null,               // postfix to html
        //     base: false,                // preserve the BASE tag or accept a string for the URL
        //     formValues: true,           // preserve input/form values
        //     canvas: false,              // copy canvas content
        //     doctypeString: '...',       // enter a different doctype for older markup
        //     removeScripts: false,       // remove script tags from print content
        //     copyTagClasses: false,      // copy classes from the html & body tag
        //     beforePrintEvent: null,     // function for printEvent in iframe
        //     beforePrint: null,          // function called before iframe is filled
        //     afterPrint: null            // function called before iframe is removed
        // });


    });
});

function calculateTotal() {
    var price = 0;

    $('.order-list .product-price').each(function (index) {

        price += parseFloat($(this).html().replace(/,/g, ''));
    });
    $('.total-price').html($.number(price, 2));

    if(price > 0) {
        $('#add-order-form-btn').removeClass('disabled');
    } else {
        $('#add-order-form-btn').addClass('disabled');
    }
}
