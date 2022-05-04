$(document).ready(function() {
    // Nhan gia tri the selectbox:hay
    //     $("select[name=select]").change(function(){ // Bắt sự kiện Onchange
    //         var select = $(this).val();//Lấy giá trị của phòng ban
    //         //Xử lý gọi ajax về server để lấy data về.
    //         // alert(select);
    //         $.get(
    //               'testajax', //Link gọi ajax về server
    //               { // Các tham số khác trường hợp này là phòng ban
    //                   'select':select
    //               },
    //             // Đây là hàm sẽ được thực thi khi ajax trả giá trị về cho client và chứa trong biến data
    //               function(data) {
    //                     //Bạn nạp data này vào thẻ div
    //                     // alert('Ket noi ngon roi');
    //                     alert(data);
    //                     // console.log(data);
    //                     // $("#hienthi").html(data);
    //              }
    //         )
    //         //--- Kết thúc xử lý ajax
    //   });
    // Ap dung ajax vao laravel
    $(".num-order").change(function(e) {
        var id = $(this).attr('data-id');
        var qty = $(this).val();
        //  console.log(id, qty);
        var data = { id: id, qty: qty };
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "cart/updateajax", //action ben controller //CartController@updateajax
            method: 'POST',
            data: {
                id: '{{csrf_token()}}',
                qty: '{{csrf_token()}}',
                //  Phai khai bao ro rang the nay
                //  Su dung ten truong du lieu kem theo '{{csrf_token()}}' de ket noi
                // _token: "{{ csrf_token() }}",
                id,
                qty
            },
            dataType: 'json',
            success: function(data) {
                //    alert("Da ket noi ngon roi");
                //    alert(data);
                $("#sub-total-" + id).text(data.sub_total);
                $("#total-price strong").text(data.total);
                $("#num").text(data.count_cart);
                $("#num_respon").text(data.count_cart);
                // $("span#num").text(data.totol_count);
                // var time = new Date().getTime();
                // $(document.body).bind("mousemove keypress", function(e) {
                //     time = new Date().getTime();
                // });

                // function refresh() {
                //     if (new Date().getTime() - time >= 1000)
                //         window.location.reload(true);
                //     else
                //         setTimeout(refresh, 1000);
                // }
                // setTimeout(refresh, 1000);
                console.log(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    });



    // addcart bang ajax(1 san pham o trang chu va trang product)
    $(".add-cart").click(function(e) {
        // Ap dung ajax vao laravel
        var id = $(this).attr('data-id');
        var data = { id: id };
        // alert(id);
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "addcartajax", //action ben controller //CartController@updateajax
            method: 'GET',
            data: {
                id: '{{csrf_token()}}',
                id,
            },
            dataType: 'text',
            success: function(data) {
                // alert('tra ve');
                swal("Đã thêm sản phẩm vào giỏ hàng", "", "success");
                var time = new Date().getTime();
                $(document.body).bind("mousemove keypress", function(e) {
                    time = new Date().getTime();
                });

                function refresh() {
                    if (new Date().getTime() - time >= 1000)
                        window.location.reload(true);
                    else
                        setTimeout(refresh, 1000);
                }
                setTimeout(refresh, 1000);
                console.log(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    });

    // Tra don hang: 

    $('#order_lookup').click(function(e) {
        e.preventDefault();
        swal("Nhập email của bạn", {
                content: "input",
                button: {
                    text: "Tìm đơn hàng",
                    // closeModal: false,
                },
            })
            .then((value) => {
                var email = value;
                // alert(email);
                var data = { email: email };
                // Lay url hien tai:3 cach
                // var pathname = window.location.pathname; // Returns path only (/path/example.html)
                var url = window.location.href; // Returns full URL (https://example.com/path/example.html)
                // var origin = window.location.origin; // Returns base URL (https://example.com)
                // alert(url);
                if (url != 'http://localhost/unimart/order_lookup') {
                    url = 'http://localhost/unimart/order_lookup';
                }
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url, //thay url bang bien url tren de vao cac trang con khong bi loi
                    method: 'GET',
                    // async: true,
                    // global: true,
                    // cache: false,
                    data: {
                        email: '{{csrf_token()}}',
                        email
                    },
                    dataType: 'text',
                    success: function(data) {
                        // alert('ket noi roi');
                        if (data == 'no') {
                            swal("Không tìm thấy đơn hàng nào trên hệ thống!", {
                                    button: {
                                        text: "Đóng",
                                        // closeModal: false,
                                    },
                                })
                                // swal(`Không tìm thấy đơn hàng nào trên hệ thống`);
                        } else {
                            $("#order_customer").html(data);

                            // function reload() {
                            //     location.reload();
                            // }
                            // setTimeout(reload, 60000);
                        }
                        // console.log(data);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            });
    });


    // ===========================================================================================
    // Tao hop bao dat hang thanh cong khong dung ajax
    // $("#order-now").click(function() {
    //     var fullname = $('#fullname').val();
    //     var email = $('#email').val();
    //     var address = $('#address').val();
    //     var note = $('#note').val();
    //     if (fullname != "" && email != "" && address != "" && phone != "") {
    //         swal("Đặt hàng thành công!", "Kiểm tra email của bạn để biết thông tin đơn hàng!", "success");
    //         if (fullname == "" && email == "" && address == "" && phone == "") {
    //             var time = new Date().getTime();
    //             $(document.body).bind("mousemove keypress", function(e) {
    //                 time = new Date().getTime();
    //             });

    //             function refresh() {
    //                 if (new Date().getTime() - time >= 1000)
    //                     window.location.reload(true);
    //                 else
    //                     setTimeout(refresh, 1000);
    //             }
    //             setTimeout(refresh, 1000);
    //         }
    //     }
    // });
    // ======================================================================================
    // CHuyen huong khi dat hang ve trang chu co hop thong bao thanh cong: 
    if ($('p.success').hasClass('success')) {
        swal("Quý khách đã đặt hàng thành công!", "Kiểm tra email của bạn để biết thông tin đơn hàng!", "success");

        function reload() {
            location.reload();
            // window.location.href = "http://localhost/projec_wedsite_shop/";
        }
        setTimeout(reload, 4000);
        // setInterval(redirect, 3000);
    }

    // Ham xac thuc email cua jquery tu xay dung
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        // var emailReg ="/^[A-Za-z0-9_.]{6,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
        return emailReg.test($email);
    }
    // ================================================================================================
    // thanh toan don hang bang ajax(Tao hop bao dat hang thanh cong khong dung ajax)
    // $("#order-now").click(function(e) {
    //     // alert("D aket noi");
    //     var fullname = $('#fullname').val();
    //     var email = $('#email').val();
    //     var address = $('#address').val();
    //     var phone = $('#phone').val();
    //     var note = $('#note').val();
    //     // Lay gia tri cua radio box khi chon trong js
    //     var payment_method = document.querySelector('input[name = "payment_method"]:checked').value;
    //     e.preventDefault();
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     if (fullname == "" || email == "" || !validateEmail(email) || address == "" || phone == "") {
    //         swal("Thông tin liên hệ phải điền đầy đủ", "Email phải đúng định dạng(có chữ @)", "error");
    //     }
    //     if (!validateEmail(email)) {
    //         swal("Email không đúng định dạng(phải có chữ @)", "", "error");
    //     }
    //     if (fullname != "" && email != "" && validateEmail(email) && address != "" && phone != "") {
    //         $.ajax({
    //             url: "ajaxorder",
    //             method: 'GET',
    //             data: {
    //                 fullname: '{{csrf_token()}}',
    //                 email: '{{csrf_token()}}',
    //                 address: '{{csrf_token()}}',
    //                 phone: '{{csrf_token()}}',
    //                 note: '{{csrf_token()}}',
    //                 payment_method: '{{csrf_token()}}',
    //                 fullname,
    //                 email,
    //                 address,
    //                 phone,
    //                 note,
    //                 payment_method,
    //             },
    //             dataType: 'text',
    //             success: function(data) {
    //                 // alert("tra ve oke roi"),
    //                 // alert(data);
    //                 // Hiển thị các trường thông báo
    //                 if (data == 'no') {
    //                     swal("Email không đúng định dạng(có chữ @)!", "", "error");
    //                 }

    //                 if (data == 'error') {
    //                     swal("Sản phẩm bạn đặt hiện không đủ hàng!", "Bạn quay lại giỏ hàng để thay đổi số lượng hoặc chọn sản phẩm khác cho phù hợp!", "error");
    //                 }
    //                 if (data == "success") {
    //                     swal("Đặt hàng thành công!", "Kiểm tra email của bạn để biết thông tin đơn hàng!", "success");

    //                     function redirect() {
    //                         window.location.href = "http://localhost/projec_wedsite_shop/";
    //                     }
    //                     setInterval(redirect, 3000);
    //                 }

    //                 console.log(data);
    //             },
    //             error: function(xhr, ajaxOptions, thrownError) {
    //                 alert(xhr.status);
    //                 alert(thrownError);
    //             },
    //         });
    //     }
    // });
    // =============================================================================================================


    // Xay dung tim kiem bang ajax
    // $("#s").change(function (e) {
    // $("#s").on('keyword', function(e)  {
    // $("#s").keypress(function(e)  {
    $('#s').keyup(function(e) {
        // Su kien keyup de bat ky tu khi nhap vao input, mem mai hon change
        var keyword = $(this).val();
        var data = { keyword: keyword };
        // alert(keyword);
        var url = window.location.href; // Returns full URL (https://example.com/path/example.html)
        // var origin = window.location.origin; // Returns base URL (https://example.com)
        // alert(url);
        if (url != 'http://localhost/unimart/search') {
            url = 'http://localhost/unimart/search';
        }
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            // url: "search",// url fixe co dinh
            url: url, //thay url bang bien url tren de vao cac trang con khong bi loi nhung lai mat link anh, vao file xu ly lai ep duong dan cho link anh, nhoc day ma van khong duoc(duoc roi, nhoc day)
            method: 'GET',
            data: {
                keyword: '{{csrf_token()}}',
                keyword,
            },
            dataType: 'text',
            success: function(data) {
                // alert("Ket noi da co");
                // alert(data);
                $("#search_product").html(data);
                console.log(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    });

    // Kich hoat xem thêm mô tả sản phẩm và ẩn đi
    $(".seemore").click(function() {
        $("#section_detail_product").toggleClass("section_detail_product");
        // $("#section_detail_product").removeClass("section_detail_product");
    });
    $(".finishseemore").click(function() {
        $("#section_detail_product").addClass("section_detail_product");
    });
    // Tao icon backtop
    $(window).scroll(function() {
        if ($(this).scrollTop()) {
            $("#back-to-top").fadeIn();
        } else {
            $("#back-to-top").fadeOut();
        };
    });
    // Nguoi dung bam vao nut bam vao back-to-top thi len dau trang
    $("#back-to-top").click(function() {
        $('html,body').animate({ scrollTop: 0 }, 800);
        // {scrollTop :0}:vi tri 0(len tren dau trang)
        // Thoi gian scroll:1s
    });

    // Cua phan admin cua unitop
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function() {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function() {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });
});
// An don hang: tac dong js tren doi tuong dong 
$(document).on("click", '#close', function(event) {
    // alert("Đa tuong tac");
    // $('#order_customer').addClass('hidden_customer');
    $('#order_customer').text('');
});
// Dong hop tim kiem
$(document).on("click", '#close-search', function(event) {
    // alert("Đa tuong tac");
    $('#s').val('');
    $('#search_product').text('');
});