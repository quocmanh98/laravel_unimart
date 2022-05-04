<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>
    {{-- View len duoc css ma mail lai ko len duoc : la that --}}
    {{-- <style type="text/css">
        table, th, td{
            border:1px solid #868585;
        }
        table{
            border-collapse:collapse;
            width:100%;
        }
        th, td{
            text-align:left;
            padding:10px;
        }
        table tr:nth-child(odd){
            background-color:#eee;
        }
        table tr:nth-child(even){
            background-color:white;
        }
        table tr:nth-child(1){
            background-color:skyblue;
        }
    </style> --}}
        {{-- <h1>Xác nhận : {{$data['key1']}}</h1>
        <h3>Thông tin khách hàng</h3>
        @if(isset($data)) --}}
                {{-- @foreach ($data as $key =>$value )
                    <p>{{$value}}</p>
                @endforeach --}}

                {{-- <p>Khách hàng : {{$data['Customer']}}</p>
                <p>MaKH : {{$data['MaKH']}}</p>
                <p>Email : {{$data['Email']}}</p>
                <p>Địa chỉ : {{$data['Address']}}</p>
                <p>Số điện thoại : {{$data['Phone']}}</p>
                <p>Thời gian đự kiến nhận hàng: {{$data['time_send']}}</p>
        @endif --}}
        {{-- Thông tin đơn hàng --}}
        <h1>Thông tin đơn hàng</h1>
        <table border="1">
            <thead class="">
                <tr>
                    <td>STT</td>
                    <td>Mã sản phẩm</td>
                    <td>Tên sản phẩm</td>
                    <td>Giá sản phẩm</td>
                    <td>Số lượng</td>
                    <td>Màu sắc</td>
                    <td>Thành tiền</td>
                </tr>
            </thead>
            <tbody>
            {{-- Do du lieu đơn hàng--}}
                    @php
                        $t=0;
                    @endphp
                    @foreach($products_order as $row)
                        @php
                            $t++;
                        @endphp
                        <tr>
                            <td>{{$t}}</td>
                            <td>{{$row->options->masp}}</td>
                            <td>
                                <p href="" title="" class="name-product">{{$row->name}}</p>
                            </td>
                            <td>{{number_format($row->price,0,',','.')}}đ</td>
                            <td>
                                 <p  class="num-order" >{{$row->qty}} </p>
                            </td>
                            <td>
                                 <p  class="name-product" >{{$row->options->color}} </p>
                            </td>
                            <td id="sub-total-{{$row->rowId}}">{{number_format($row->total,0,',','.')}}đ</td>
                        </tr>
                    @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="clearfix">
                            {{-- <p id="total-price" class="fl-right">Tổng tiền: <strong>{{Cart::total()}}đ</strong></p> --}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
</body>
</html>



