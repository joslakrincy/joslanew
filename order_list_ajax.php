<?php
session_start();
include("../../include/globals.php");
include_once '../../include/connect.php';
include_once '../include/Get_Orders.php';
$orders = new GetOrders();
if (isSet($_POST['action'])) {
    $action = mysqli_real_escape_string($mysqli, $_POST['action']);
    if ($action == "search") {
        $orderstatus = "";
        $norders = "";
        $lastid = "0";
        $orderdate = "";
		$orderdate1 ="";
        $orderid = mysqli_real_escape_string($mysqli, $_POST['searchtext']);
    } elseif ($action == "searchdate") {

        $orderdate = $_POST['orderdate'];
		$orderdate1 = $_POST['orderdate1'];
        $orderstatus = "";
        $orderid = "";
        $norders = "";
        $lastid = "0";
    } elseif ($action == "shopname") {

        $norders = $_POST['ordershop'];
        $orderstatus = "";
        $orderid = "";
        $lastid = "0";
    } 
    else {

        $orderstatus = mysqli_real_escape_string($mysqli, $_POST['orderstatus']);
        $orderid = "";
        $norders = "";
        $lastid = "0";
        $orderdate = "";
		$orderdate1 ="";
    }



    $updatesarray = $orders->Search_Orders($orderstatus, $orderid, $norders, $orderdate,$orderdate1, $lastid);
    $total = count($updatesarray);
    if ($updatesarray) {

        $sno = 0;
        foreach ($updatesarray as $data) {
            $sno = $sno + 1;
            $itemsid = array();
            $orderid = $data[0]['orderid'];
            /* $shopping_amount = $data[0]['shopping_amount'];  */
            $createdOn = $data[0]['str_date'];
            $fname = $data[0]['firstName'];
            $lname = $data[0]['lastName'];
            $status = $data[0]['int_status'];
            $address = $data[0]['address'];
            $useInventory = $data[0]['useInventory'];

            $email = $data[0]['email'];
            $callingcode = $data[0]['callingcode'];
            $phone = $data[0]['phone'];
            $quantity = $data[0]['int_quantity'];
            $float_unitprice = $data[0]['float_unitprice'];
            $str_message = $data[0]['str_message'];
            $sellerid = $data[0]['int_sellerid'];
            $time = $data[0]['int_created'];
            $uniqueId = $data[0]['uniqueId'];
            $pimage = $data[0]['pimage'];
            $couriername = $data[0]['couriername'];
            $courierid = $data[0]['courierid'];
            $courierlink = $data[0]['courierlink'];
            $courierdate = $data[0]['courierdate'];
            $order_status = $data[0]['order_status'];
            $delivery_charge = $data[0]['delivery_charge'];
            $delivery_address = $data[0]['delivery_address'];
            $delivery_address = unserialize(base64_decode($delivery_address));
            $delivery_address = ucfirst($delivery_address['deliveryname']) . ", " . ucfirst($delivery_address['address']) . ", " . ucfirst($delivery_address['city']) .
                    ", " . ucfirst($delivery_address['state']) . ", " . ucfirst($delivery_address['pinnumber']) . ", " . ucfirst($delivery_address['country']) . ", " . $delivery_address['mobilenumber'];
            $delivery_address = stripslashes($delivery_address);
            $buyerid = $data[0]['int_uid'];
            $usecod = $data[0]['usecod'];


            $status = $data[0]['int_status'];
            $intid = $data[0]['int_id'];
            $seller_id = $items['int_uid'];

            if ($status == 0) {
                $str_status = "New";
            } elseif ($status == 1) {
                $str_status = "Accepted";
            } elseif ($status == 2) {
                $str_status = "Paid";
            } elseif ($status == 3) {
                $str_status = "Deliverd";
            } elseif ($status == 4) {
                $str_status = "Rejected";
            } elseif ($status == 5) {
                $str_status = "Delayed";
            }


            $shopping_amount = 0;
            foreach ($data as $items) {

                $intid = $items['int_id'];
                array_push($itemsid, $intid);
                $int_pid = $items['int_pid'];
                $uniqueId = $items['uniqueId'];
                $imageo = $items['name'];
                $productimage = str_replace("o_", "s_", $imageo);
                $pname = $items['str_sname'];
                $webname = $items['webname'];
                $str_details = $items['str_details'];
                $quantity = $items['int_quantity'];
                $float_unitprice = $items['float_unitprice'];

                // $currency_code = $items['currency_code'];
                // $currency_symbol = $items['currency_symbol'];
                $shop_name = $items['shop_name'];


                $uid = $items['int_uid'];
                //- $status = $items['int_status'];
                $scat = $items['scat'];
                $cweight = $items['cweight'];
                $cmassage = $items['cmassage'];
                $dt = $items['ddate'];
                $cshape = $items['cshape'];
                $doption = $items['doption'];
                $pdate = $items['pdate'];
                $comboarray = $items['comboarray'];

                $shopping_amount = $shopping_amount + ($float_unitprice * $quantity);
                ?>  

                <tr class="orderinfo" data-oid="<?php echo $orderid; ?>" data-toggle="modal">
                    <td><?php echo $sno; ?></td>
                    <td><?php echo $orderid; ?> </td>
                    <td><a href="#"><?php echo $shop_name; ?></td>
                    <td><?php echo $createdOn; ?></td>
                    <td><i class="fa fa-inr">&nbsp;</i><?php echo $currency_symbol . " " . $shopping_amount; ?></td>
                    <td><label class="label label-primary"><?php echo $str_status; ?></label></td>
                </tr>


                <?php
            }
        }
        ?>
        <script>
            $("#ordcount").text("Order: <?php echo $total ?>");

        </script>
        <?php
    } else {
        ?>
        <tr class="orderinfo">
            <td colspan="5"></td>
        </tr>
        <?php
    }
}
?>

<script>

    $(function () {

        $('.orderinfo').click(function () {

            var oid = $(this).data('oid');
            $.ajax({
                url: 'order_list_modal.php',
                type: 'post',
                data: {"oid": oid},
                cache: false,
                success: function (response) {
                    $('#pro_body').html(response);
                    $('#proModal').modal('show');

                }
            });
        });

    });
</script>
