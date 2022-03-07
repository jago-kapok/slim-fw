<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class KasirModel
{
    public static function kasirTransaction() {
      $today = date("Y-m-d");
      $database = DatabaseFactory::getFactory()->getConnection();
      try {
          
          $database->beginTransaction();

          // Transaction 1: Make transaction number
          $sql = "SELECT `transaction_number` FROM `sales_order` WHERE `created_timestamp` > :today ORDER BY `created_timestamp` DESC LIMIT 1";

          $insert = $database->prepare($sql);
          $insert->execute(array(":today" => $today));
          $last_transaction_number = $insert->fetch();
          
          $transaction_number = $last_transaction_number->transaction_number;
          $find_integer = explode('/', $transaction_number);
          $transaction_number = $find_integer[0];
          $transaction_number = FormaterModel::getNumberOnly($transaction_number);
          $transaction_number = (int)$transaction_number + 1;
          $transaction_number = Config::get('COMPANY_CODE') . ' ' . $transaction_number . '/' . date("dmy");

          // Transaction 1: Insert Detail Pembelian
          $sql = "INSERT INTO sales_order_list (uid, transaction_number, material_code, quantity, selling_price, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_number, :material_code, :quantity, :selling_price, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);

          $product_list   = explode(' ___ ', Request::post('product_list'));
          $product_list   = array_filter($product_list);
          //echo '<pre>';var_dump($product_list);echo '</pre>';exit;

          $detail_penjualan = '';
          $nomer_detail_penjualan = 1;
          foreach ($product_list as $key => $value) {
              $product   = explode('---', $value);
              //echo '<pre>';var_dump($product);echo '</pre>';
              $product_code = trim($product[0]);
              $product_quantity = FormaterModel::getNumberOnly($product[1]);
              $product_price = FormaterModel::getNumberOnly($product[2]);
              if (!empty($product_code  ) AND !empty($product_quantity)) {
                $insert->execute(array(
                                ':uid'    => GenericModel::guid(),
                                ':transaction_number'    => $transaction_number,
                                ':material_code'    => $product_code,
                                ':quantity'    => $product_quantity,
                                ':selling_price'    => $product_price,
                                ':creator_id'    => SESSION::get('uid'),
                                ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                            ));
                $detail_penjualan .= $nomer_detail_penjualan . '. ' . $product_code . ' (' . $product_quantity . ')<br>';
                $nomer_detail_penjualan++;
              }
          }

          $priceNet = FormaterModel::getNumberOnly(Request::post('priceNet'));
          $priceGross = (int)FormaterModel::getNumberOnly(Request::post('priceGross'));
          $discount_in_percentage = (int)FormaterModel::getNumberOnly(Request::post('diskonPersen'));
          $discount_in_money    = (int)FormaterModel::getNumberOnly(Request::post('potonganHarga'));
          $discount_total = (($discount_in_percentage/100) * $priceGross) + $discount_in_money;
          $pembayaran = FormaterModel::getNumberOnly(Request::post('pembayaran'));
          $kembalian = FormaterModel::getNumberOnly(Request::post('kembalian'));
          $customer_table_number = !empty(Request::post('customer_table_number')) ? Request::post('customer_table_number') : '';
          $edc_bank = !empty(Request::post('edc_bank')) ? Request::post('edc_bank') : '';
          $edc_reference = !empty(Request::post('edc_reference')) ? Request::post('edc_reference') : '';
          $customer_name = !empty(Request::post('customer_name')) ? Request::post('customer_name') : '';

          $sql = "INSERT INTO sales_order (transaction_number, sales_channel, status, price_net, price_gross, discount_in_percentage, discount_in_money, discount_total, received_payment, payment_return, customer_table_number, customer_name, customer_id, edc_bank, edc_reference, creator_id, created_timestamp)
                            VALUES (:transaction_number, :sales_channel, :status, :price_net, :price_gross, :discount_in_percentage, :discount_in_money, :discount_total, :received_payment, :payment_return, :customer_table_number, :customer_name, :customer_id, :edc_bank, :edc_reference, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                          ':transaction_number'    => $transaction_number,
                            ':sales_channel' => 'point of sales',
                          ':status' => 1,
                          ':price_net' => $priceNet,
                          ':price_gross' => $priceGross,
                          ':discount_in_percentage'    => $discount_in_percentage,
                          ':discount_in_money'    => $discount_in_money,
                          ':discount_total'    => $discount_total,
                          ':received_payment'    => $pembayaran,
                          ':payment_return'    => $kembalian,
                          ':customer_table_number'    => $customer_table_number,
                          ':customer_name'    => $customer_name,
                          ':customer_id'    => 'inhouse',
                          ':edc_bank'    => $edc_bank,
                          ':edc_reference'    => $edc_reference,
                          ':creator_id'    => SESSION::get('uid'),
                          ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                  ));

          
          $sql = "INSERT INTO payment_transaction (uid, transaction_name, transaction_code, transaction_category, transaction_type, status, currency, debit, payment_type, payment_due_date, payment_disbursement, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_name, :transaction_code, :transaction_category, :transaction_type, :status, :currency, :debit, :payment_type, :payment_due_date, :payment_disbursement, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                        ':uid'    => Request::post('token'),
                        ':transaction_name'    => 'kasir penjualan',
                        ':transaction_code' => $transaction_number,
                        ':transaction_category' => 'penjualan',
                        ':transaction_type' => 'point of sale',
                        ':status' => 1,
                        ':currency' => 'IDR',
                        ':debit' => FormaterModel::getNumberOnly(Request::post('priceNet')),
                        ':payment_type' => 'cash',
                        ':payment_due_date' =>date('Y-m-d'),
                        ':payment_disbursement'    => date('Y-m-d'),
                        ':creator_id'    => SESSION::get('uid'),
                        ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                    ));

          // commit the transaction
          $database->commit();

          //send email transaction success by check if rows affected
          if ($insert->rowCount() >= 0) {
              $email = array();
              $email[] = 'jabrik.ta01@gmail.com';
              $email[] = Config::get('EMAIL_NOTIFICATION');

              $email_creator = SESSION::get('full_name');
              $email_subject = "Transaksi Penjualan Kedai Jumini NO: " . $transaction_number . ' oleh ' . ucwords($email_creator);
              $body ='Total Penjualan ' . number_format($priceNet, 0) . ' untuk Nomer Transaksi ' . $transaction_number . '. Klik link berikut untuk melihat detail transaksi ' .   Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction_number)  . '<br><br><br>DETAIL TRANSAKSI:<br>' . 
                  'Total Pembelian: ' . number_format($priceGross, 0) . '<br>' .
                  'Total Discount: ' . number_format($discount_total, 0) . '<br>' .
                  'Total Tagihan: ' . number_format($priceNet, 0) . '<br>' .
                  'Pembayaran: ' . number_format($pembayaran, 0) . '<br>' .
                  'Kembalian: ' . number_format($kembalian, 0) . '<br>' .
                  'Customer: ' . Request::post('customerName') . '<br>' .
                  'Item Penjualan: ' . '<br>' . $detail_penjualan;
              $mail = new Mail;
              $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                  Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
              );
           }

          return $transaction_number;

      } catch (PDOException $e) {
          $database->rollBack();
          die('GAGAL');
      }

    }

    public static function consignmentTransaction() {
      $today = date("Y-m-d");
      //echo '<pre>';var_dump($_POST);echo '</pre>';exit;
      $database = DatabaseFactory::getFactory()->getConnection();

      try {
          
          //populate post data
          $price_net = FormaterModel::getNumberOnly(Request::post('price_net'));
          $customer = explode('---', Request::post('customer'));
          $customer_id = $customer[0];
          $customer_name= $customer[1];
          $payment_due_date    = date("Y-m-d",strtotime(Request::post('payment_due_date')));

          $database->beginTransaction();

          // Transaction 1: Make transaction number
          $sql = "SELECT `transaction_number` FROM `sales_order` WHERE `created_timestamp` > :today ORDER BY `created_timestamp` DESC LIMIT 1";

          $insert = $database->prepare($sql);
          $insert->execute(array(":today" => $today));
          $last_transaction_number = $insert->fetch();
          
          $transaction_number = $last_transaction_number->transaction_number;
          $find_integer = explode('/', $transaction_number);
          $transaction_number = $find_integer[0];
          $transaction_number = FormaterModel::getNumberOnly($transaction_number);
          $transaction_number = (int)$transaction_number + 1;
          $transaction_number = Config::get('COMPANY_CODE') . ' ' . $transaction_number . '/' . date("dmy");

          // Transaction 2: Insert list barang yang dibeli
          $sql = "INSERT INTO sales_order_list (uid, transaction_number, material_code, quantity, selling_price, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_number, :material_code, :quantity, :selling_price, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);

          $product_list   = explode(' ___ ', Request::post('product_list'));
          $product_list   = array_filter($product_list);
          

          $detail_penjualan = '';
          $nomer_detail_penjualan = 1;
          foreach ($product_list as $key => $value) {
              $product   = explode('---', $value);
              //echo '<pre>';var_dump($product);echo '</pre>';
              $product_code = trim($product[0]);
              $product_quantity = FormaterModel::getNumberOnly($product[1]);
              $product_price = FormaterModel::getNumberOnly($product[2]);
              if (!empty($product_code  ) AND !empty($product_quantity)) {
                    //Insert detail material penjualan
                    $insert->execute(array(
                                    ':uid'    => GenericModel::guid(),
                                    ':transaction_number'    => $transaction_number,
                                    ':material_code'    => $product_code,
                                    ':quantity'    => $product_quantity,
                                    ':selling_price'    => $product_price,
                                    ':creator_id'    => SESSION::get('uid'),
                                    ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                                ));
                    $detail_penjualan .= $nomer_detail_penjualan . '. ' . $product_code . ' (' . $product_quantity . ')<br>';
                    $nomer_detail_penjualan++;
              }
          }

          // Transaction 3: Kurangi Stock Material dari item yang dijual
          $sql = "INSERT INTO material_list_out (uid, transaction_number, material_code, quantity_delivered, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_number, :material_code, :quantity_delivered, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          foreach ($product_list as $key => $value) {
              $product   = explode('---', $value);
              //echo '<pre>';var_dump($product);echo '</pre>';
              $product_code = trim($product[0]);
              $product_quantity = FormaterModel::getNumberOnly($product[1]);
              $product_price = FormaterModel::getNumberOnly($product[2]);
              if (!empty($product_code  ) AND !empty($product_quantity)) {

                  //Kurangi Stock Material
                  if (substr($product_code, 0, 4) == 'BOM.') {
                      $bom_list_sql = "SELECT
                                `material_list_formulation`.`material_code`,
                                SUM(`material_list_formulation`.`unit_per_quantity`) AS `unit_per_quantity`
                            FROM
                                `material_list_formulation`
                            WHERE
                                `material_list_formulation`.`formulation_code` = :product_code
                            GROUP BY
                                `material_list_formulation`.`material_code`";
                      $select = $database->prepare($bom_list_sql);
                      $select->execute(array(
                          ':product_code'    => $product_code
                      ));
                      $bom_list = $select->fetchAll();
                        var_dump($bom_list);
                      foreach ($bom_list as $bom_key => $bom_value) {
                          $formulation_qty = $product_quantity * $bom_value->unit_per_quantity;

                          //insert only if qty > 0, not spaming database and keep view simple and tidy
                          if ($formulation_qty > 0) {
                              $insert->execute(array(
                                  ':uid'    => GenericModel::guid(),
                                  ':transaction_number'    => $transaction_number,
                                  ':material_code'    => $bom_value->material_code,
                                  ':quantity_delivered'    => $formulation_qty,
                                  ':creator_id'    => SESSION::get('uid'),
                                  ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                              ));
                          }
                      }
                  } else { // Not BOM
                      $insert->execute(array(
                          ':uid'    => GenericModel::guid(),
                          ':transaction_number'    => $transaction_number,
                          ':material_code'    => $product_code,
                          ':quantity_delivered'    => $product_quantity,
                          ':creator_id'    => SESSION::get('uid'),
                          ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                      ));
                  }
              }
          }



          // Transaction 4: Masukkan sales order number
          $sql = "INSERT INTO sales_order (transaction_number, status, price_net, customer_id, customer_name, creator_id, created_timestamp)
                            VALUES (:transaction_number, :status, :price_net, :customer_id, :customer_name, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                          ':transaction_number' => $transaction_number,
                          ':status' => 1,
                          ':price_net' => $price_net,
                          ':customer_id' => $customer_id,
                          ':customer_name'    => $customer_name,
                          ':creator_id' => SESSION::get('uid'),
                          ':created_timestamp' => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                  ));

          // commit the transaction
          $database->commit();

          //send email transaction success by check if rows affected
          if ($insert->rowCount() >= 0) {
              $email = array();
              $email[] = 'jabrik.ta01@gmail.com';
              $email[] = Config::get('EMAIL_NOTIFICATION');

              $email_creator = SESSION::get('full_name');
              $email_subject = "Transaksi Penjualan Kedai Jumini NO: " . $transaction_number . ' oleh ' . ucwords($email_creator);
              $body ='Total Penjualan ' . number_format($price_net, 0) . ' untuk Nomer Transaksi ' . $transaction_number . '. Klik link berikut untuk melihat detail transaksi ' .   Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction_number)  . '<br><br><br>DETAIL TRANSAKSI:<br>' .
                  'Customer: ' . $customer_name . '<br>' .
                  'Item Penjualan: ' . '<br>' . $detail_penjualan;
              $mail = new Mail;
              $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                  Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
              );
           }

          return $transaction_number;

      } catch (PDOException $e) {
          $database->rollBack();
          die('GAGAL');
      }

    }

    public static function getTransaction($transaction_number) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`price_net`,
                    `sales_order`.`price_gross`,
                    `sales_order`.`discount_in_percentage`,
                    `sales_order`.`discount_in_money`,
                    `sales_order`.`discount_total`,
                    `sales_order`.`received_payment`,
                    `sales_order`.`payment_return`,
                    `sales_order`.`customer_table_number`,
                    `sales_order`.`customer_name`,
                    `sales_order`.`created_timestamp`,
                    `sales_order_list`.`quantity`,
                    `sales_order_list`.`selling_price`,
                    `material_list`.`material_name` as `material_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` ON`material_list`.`material_code` = `sales_order_list`.`material_code`
                WHERE `sales_order`.`transaction_number` = :transaction_number";

        $query = $database->prepare($sql);
        $query->execute(array(
                            ':transaction_number' => $transaction_number
                            ));

        return $query->fetchAll();       
    }

    public static function getTransactionClinic($transaction_number) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`price_net`,
                    `sales_order`.`price_gross`,
                    `sales_order`.`discount_in_percentage`,
                    `sales_order`.`discount_in_money`,
                    `sales_order`.`discount_total`,
                    `sales_order`.`received_payment`,
                    `sales_order`.`payment_return`,
                    `sales_order`.`customer_table_number`,
                    `sales_order`.`customer_name`,
                    `sales_order`.`created_timestamp`,
                    `sales_order_list`.`quantity`,
                    `sales_order_list`.`selling_price`,
                    `users`.`full_name`,
                    `material_list`.`material_name` as `material_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` ON`material_list`.`material_code` = `sales_order_list`.`material_code`
                LEFT JOIN
                    `users` ON `users`.`uid` = `sales_order`.`person_in_charge`
                WHERE `sales_order`.`transaction_number` = :transaction_number";

        $query = $database->prepare($sql);
        $query->execute(array(
                            ':transaction_number' => $transaction_number
                            ));

        return $query->fetchAll();       
    }

    public static function clinicTransaction() {
      $today = date("Y-m-d");
      $database = DatabaseFactory::getFactory()->getConnection();
      try {
          
          $database->beginTransaction();

          // Transaction 1: Make transaction number
          $sql = "SELECT `transaction_number` FROM `sales_order` WHERE `created_timestamp` > :today ORDER BY `created_timestamp` DESC LIMIT 1";

          $select_transaction_number = $database->prepare($sql);
          $select_transaction_number->execute(array(":today" => $today));
          $last_transaction_number = $select_transaction_number->fetch();
          
          $transaction_number = $last_transaction_number->transaction_number;
          $find_integer = explode('/', $transaction_number);
          $transaction_number = $find_integer[0];
          $transaction_number = FormaterModel::getNumberOnly($transaction_number);
          $transaction_number = (int)$transaction_number + 1;
          $transaction_number = Config::get('COMPANY_CODE') . ' ' . $transaction_number . '/' . date("dmy");

          // Transaction 2: Insert Detail Pembelian
          $sql = "INSERT INTO sales_order_list (uid, transaction_number, material_code, quantity, selling_price, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_number, :material_code, :quantity, :selling_price, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);

          $product_list   = explode(' ___ ', Request::post('product_list'));
          $product_list   = array_filter($product_list);
          //echo '<pre>';var_dump($product_list);echo '</pre>';exit;

          $detail_penjualan = '';
          $nomer_detail_penjualan = 1;
          foreach ($product_list as $key => $value) {
              $product   = explode('---', $value);
              //echo '<pre>';var_dump($product);echo '</pre>';
              $product_code = trim($product[0]);
              $product_quantity = FormaterModel::getNumberOnly($product[1]);
              $product_price = FormaterModel::getNumberOnly($product[2]);
              if (!empty($product_code  ) AND !empty($product_quantity)) {
                $insert->execute(array(
                                ':uid'    => GenericModel::guid(),
                                ':transaction_number'    => $transaction_number,
                                ':material_code'    => $product_code,
                                ':quantity'    => $product_quantity,
                                ':selling_price'    => $product_price,
                                ':creator_id'    => SESSION::get('uid'),
                                ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                            ));
                $detail_penjualan .= $nomer_detail_penjualan . '. ' . $product_code . ' (' . $product_quantity . ')<br>';
                $nomer_detail_penjualan++;
              }
          }

          // Transaction 3: Get Therapist Name
          $sql = "SELECT
                    `users`.`uid`, `users`.`full_name`
                  FROM
                    `users`
                  LEFT JOIN
                    `users_attendance_log` ON `users_attendance_log`.`user_id` = `users`.`uid`
                  WHERE
                    `users`.`department` = 'therapist' AND `users`.`is_active` = 1 AND `users_attendance_log`.`created_timestamp` > :today ORDER BY `users_attendance_log`.`created_timestamp` ASC LIMIT 1";

          $select_therapist = $database->prepare($sql);
          $select_therapist->execute(array(":today" => $today));
          $therapist_name = $select_therapist->fetch();

          $person_in_charge = $therapist_name->uid;
          $priceNet = FormaterModel::getNumberOnly(Request::post('priceNet'));
          $priceGross = (int)FormaterModel::getNumberOnly(Request::post('priceGross'));
          $discount_in_percentage = (int)FormaterModel::getNumberOnly(Request::post('diskonPersen'));
          $discount_in_money    = (int)FormaterModel::getNumberOnly(Request::post('potonganHarga'));
          $discount_total = (($discount_in_percentage/100) * $priceGross) + $discount_in_money;
          $pembayaran = FormaterModel::getNumberOnly(Request::post('pembayaran'));
          $kembalian = FormaterModel::getNumberOnly(Request::post('kembalian'));
          $memberID = Request::post('memberID');
          $edc_bank = !empty(Request::post('edc_bank')) ? Request::post('edc_bank') : '';
          $edc_reference = !empty(Request::post('edc_reference')) ? Request::post('edc_reference') : '';
          $customer_name = !empty(Request::post('customer_name')) ? Request::post('customer_name') : '';

          $sql = "INSERT INTO sales_order (transaction_number, sales_channel, person_in_charge, status, price_net, price_gross, discount_in_percentage, discount_in_money, discount_total, received_payment, payment_return, customer_id, edc_bank, edc_reference, creator_id, created_timestamp)
                            VALUES (:transaction_number, :sales_channel, :person_in_charge, :status, :price_net, :price_gross, :discount_in_percentage, :discount_in_money, :discount_total, :received_payment, :payment_return, :customer_id, :edc_bank, :edc_reference, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                          ':transaction_number'    => $transaction_number,
                          ':sales_channel' => 'point of sales',
                          ':person_in_charge' => $person_in_charge,
                          ':status' => 1,
                          ':price_net' => $priceNet,
                          ':price_gross' => $priceGross,
                          ':discount_in_percentage'    => $discount_in_percentage,
                          ':discount_in_money'    => $discount_in_money,
                          ':discount_total'    => $discount_total,
                          ':received_payment'    => $pembayaran,
                          ':payment_return'    => $kembalian,
                          ':customer_id'    => $memberID,
                          ':edc_bank'    => $edc_bank,
                          ':edc_reference'    => $edc_reference,
                          ':creator_id'    => SESSION::get('uid'),
                          ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                  ));

          
          $sql = "INSERT INTO payment_transaction (uid, transaction_name, transaction_code, transaction_category, transaction_type, status, currency, debit, payment_type, payment_due_date, payment_disbursement, creator_id, created_timestamp)
                            VALUES (:uid, :transaction_name, :transaction_code, :transaction_category, :transaction_type, :status, :currency, :debit, :payment_type, :payment_due_date, :payment_disbursement, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                        ':uid'    => Request::post('token'),
                        ':transaction_name'    => 'kasir penjualan',
                        ':transaction_code' => $transaction_number,
                        ':transaction_category' => 'penjualan',
                        ':transaction_type' => 'point of sale',
                        ':status' => 1,
                        ':currency' => 'idr',
                        ':debit' => FormaterModel::getNumberOnly(Request::post('priceNet')),
                        ':payment_type' => 'cash',
                        ':payment_due_date' =>date('Y-m-d'),
                        ':payment_disbursement'    => date('Y-m-d'),
                        ':creator_id'    => SESSION::get('uid'),
                        ':created_timestamp'    => date("Y-m-d H:i:s.").gettimeofday()["usec"],
                    ));

          //update therapist to in active
          $sql = "UPDATE `users` SET `is_active` = 0 WHERE `uid` = :uid";

          $select_transaction_number = $database->prepare($sql);
          $select_transaction_number->execute(array(":uid" => $person_in_charge));

          // commit the transaction
          $database->commit();

          //send email transaction success by check if rows affected
          if ($insert->rowCount() >= 0) {
              $email = array();
              $email[] = 'jabrik.ta01@gmail.com';
              $email[] = Config::get('EMAIL_NOTIFICATION');

              $email_creator = SESSION::get('full_name');
              $email_subject = "Transaksi Penjualan Kedai Jumini NO: " . $transaction_number . ' oleh ' . ucwords($email_creator);
              $body ='Total Penjualan ' . number_format($priceNet, 0) . ' untuk Nomer Transaksi ' . $transaction_number . '. Klik link berikut untuk melihat detail transaksi ' .   Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction_number)  . '<br><br><br>DETAIL TRANSAKSI:<br>' . 
                  'Total Pembelian: ' . number_format($priceGross, 0) . '<br>' .
                  'Total Discount: ' . number_format($discount_total, 0) . '<br>' .
                  'Total Tagihan: ' . number_format($priceNet, 0) . '<br>' .
                  'Pembayaran: ' . number_format($pembayaran, 0) . '<br>' .
                  'Kembalian: ' . number_format($kembalian, 0) . '<br>' .
                  'Customer: ' . Request::post('customerName') . '<br>' .
                  'Item Penjualan: ' . '<br>' . $detail_penjualan;
              $mail = new Mail;
              $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                  Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
              );
           }

          return $transaction_number;

      } catch (PDOException $e) {
          $database->rollBack();
          die('GAGAL');
      }

    }

    public static function memberActive($member_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        //echo $oke = "SELECT `$field` FROM `$table` WHERE `$field` = '$value' LIMIT 1";
        $query = $database->prepare("SELECT `is_active` FROM `contact` WHERE `contact_id` = :contact_id AND `is_active` = 1 LIMIT 1");
        $query->execute(array(':contact_id' => $member_id));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

}
