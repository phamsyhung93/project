<?php 
require '../config.php';
$carts = get_cart();
if(isset($_POST['submit'])) {

    $email2 = $_POST['email'];

  $body = '';
   $body .='<div class="col-sm-8" style="margin-top: 5px;">
                <table class="table table-bordered table-condensed" >
                    <thead>
                        <tr class="text-center">                            
                            <th width="30%">Tên sản phẩm</th>
                            <th width="20%">Giá</th>
                            <th width="10%">Số lượng</th>
                            <th width="20%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-list-cart">';

                        if(count($carts)) : foreach($carts as $p) : 

                            $body .=
                            '<tr class="item_cart">                                
                                <td>'.$p['name'] .'</td>
                                <td>'.number_format($p['price'],0,' ','.').' đ/kg</td>
                                <td>
                                    '.$p['quantity'].'
                                </td>
                                <td>
                                    '.number_format(thanh_tien($p['id']),0,' ','.') .' đ
                                </td>

                            </tr>';

                     endforeach; endif; 

                     $body .='</tbody>
                </table>
                <div id="tbody-list-all-cart">
                    <div class="row-total">
                        <div class="float-left">
                            <h3>Tổng tiền</h3>
                        </div>
                        <!--End align-left-->
                        <div class="float-right">
                            <p>'.number_format(get_cost(),0,' ','.').' VNĐ</p>
                        </div>
                        
                    </div>

                </div>
                                      
            </div>';
            $body .= 
            
                
                    '<p>Tài khoản đăng nhập của quý khách là : </p>' .$_POST['email'].
                      '<p>Nếu đây là lần đầu mua hàng của mình hãy đăng nhập bằng pass mặc định 123456.</p>      
                    
                    <p>Bạn hãy truy cập vào tài khoản trên "http://localhost:81/Clem/index.php?route=login&actions=login-register"để cập nhập lại thông tin.</p>
                    <p>Cảm ơn quý khác đã mua hàng!.</p>'
                
            ;



    require 'mailler/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 3;                              // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'tungtran1706@gmail.com';                 // SMTP username
    $mail->Password = '171093ik';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('systemtest@gmail.com', 'Mailer');
    $mail->addAddress($email2, 'Joe User');     // Add a recipient
    
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Ban vua dat hang tren website FRESH FOOD cua chung toi';
    // $mail->Body    = $body;
    $mail->Body    = $body;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
if (!empty($_POST)) {
	$full_name = $_POST['full_name'];
	$email = $_POST['email'];	
	
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$total_amount = $_POST['total_amount'];
	$note = $_POST['note'];
	if (!empty($email)) {
		$user = get_one('user','email',$email);
		if ($user) {
			$uid = $user['user_id'];
		}else{
			$uid = insert('user',[
				'username' => $email,
				'email' => $email,
				'password' => MD5(123456),
				'phone' => $phone,
				'full_name' => $full_name,
				'address' => $address,
			]);
		}

		$oid = insert('`order`',[
			'customer_id' => $uid,
			'total_amount' => $total_amount,
			'status' => 0,
			'note' => $note,
			'created_date' => time(),
		]);

		foreach ($carts as $cart) {
			insert('order_detail',[
				'order_id' => $oid,
				'product_id' => $cart['id'],
				'quantity' => $cart['quantity'],
				'price' => $cart['price'],
				'return_status' => 0
			]);
			 
		}

		echo 1;
		clear_cart();
	}else{
		echo 0;
	}

}


?>