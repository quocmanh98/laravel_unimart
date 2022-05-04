<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

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
    <h1 style="font-size:20px;margin:8px 0px;">Xác nhận : {{ $data['key1'] }}</h1>
    <h1 style="font-size:20px;margin:8px 0px;">Thông tin khách hàng</h1>
    @if (isset($data))
        {{-- @foreach ($data as $key => $value)
                    <p>{{$value}}</p>
                @endforeach --}}

        <p style="font-size:18px;margin:7px 0px;">Khách hàng : {{ $data['Customer'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">MaKH : {{ $data['MaKH'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">Email : {{ $data['Email'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">Địa chỉ : {{ $data['Address'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">Số điện thoại : {{ $data['Phone'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">Phương thức thanh toán : {{ $data['payment_method'] }}</p>
        <p style="font-size:18px;margin:7px 0px;">Thời gian dự kiến nhận hàng: {{ $data['time_send'] }}</p>
    @endif
    {{-- Thông tin đơn hàng --}}
    <h1 style="font-size:20px;margin:8px 0px;">Thông tin đơn hàng</h1>
    <table border="1" style="border-collapse:collapse;border-top:1px solid #ccc;
        border-bottom:1px solid #ccc;">
        <thead class="">
            <tr style="text-align:center;background:#1B9CFC;font-size:20px;color:white";>
                <td style="padding:7px;">STT</td>
                <td style="padding:7px;">Mã sản phẩm</td>
                <td style="padding:7px;">Tên sản phẩm</td>
                <td style="padding:7px;">Giá sản phẩm</td>
                <td style="padding:7px;">Số lượng</td>
                <td style="padding:7px;">Màu sắc</td>
                <td style="padding:7px;">Thành tiền</td>
            </tr>
        </thead>
        <tbody>
            {{-- Do du lieu đơn hàng --}}
            @php
                $t = 0;
            @endphp
            @if (count($data['order']) == 4)
                <tr>
                    <td style="text-align:center;font-size:18px;padding:7px;">1</td>
                    <td style="text-align:center;font-size:18px;padding:7px;">{{ $data['order']['options']->masp }}</td>
                    <td style="font-size:18px;padding:7px;">{{ $data['order']['name'] }}</td>
                    <td style="text-align:center;font-size:18px;padding:7px;">
                        {{ number_format($data['order']['price'], 0, ',', '.') }}đ</td>
                    <td style="text-align:center;font-size:18px;padding:7px;">{{ $data['order']['qty'] }}</td>
                    <td style="text-align:center;font-size:18px;padding:7px;">{{ $data['order']['options']->color }}
                    </td>
                    <td style="text-align:center;font-size:18px;padding:7px;">
                        {{ number_format($data['total_cart'], 0, ',', '.') }}đ</td>
                </tr>
            @else
                @foreach ($data['order'] as $row)
                    @php
                        $t++;
                    @endphp
                    <tr>
                        <td style="text-align:center;font-size:18px;padding:7px;">{{ $t }}</td>
                        <td style="text-align:center;font-size:18px;padding:7px;">{{ $row->options->masp }}</td>
                        <td style="font-size:18px;padding:7px;">{{ $row->name }}</td>
                        <td style="text-align:center;font-size:18px;padding:7px;">
                            {{ number_format($row->price, 0, ',', '.') }}đ</td>
                        <td style="text-align:center;font-size:18px;padding:7px;">{{ $row->qty }}</td>
                        <td style="text-align:center;font-size:18px;padding:7px;">{{ $row->options->color }}</td>
                        <td style="text-align:center;font-size:18px;padding:7px;">
                            {{ number_format($row->total, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="background:#1B9CFC;color:white;">
                    <p style="font-size:20px;margin:7px;">Tổng tiền:
                        <strong>{{ number_format($data['total_cart'], 0, ',', '.') }}đ</strong></p>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
