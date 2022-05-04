<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    //Phải khai báo thằng này để định nghĩa thuộc tính data là dữ liệu
    //  Khai bao thang function __construct( $data )

    public function __construct( $data ) {
        // Khi chay chuong trinh phuong thuc construct se nap du lieu data dau tien truyen DemoController sang
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view( 'view.name' );
        //cua he thong tu tao
        // cua minh tao ra
        return $this->view( 'mailcustomer' )
        ->from( 'tuanss41@gmail.com', 'Hệ thống đã xác nhận đơn hàng thành công' )
        // ->subject( 'Thư gửi mail đã xác nhận thành công' )
        ->with( $this->data );
    }
    
}
