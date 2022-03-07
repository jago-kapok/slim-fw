<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class ConsignmentModel
{
    public static function consignmentTransaction() {
      $today = date("Y-m-d");
      //echo '<pre>';var_dump($_POST);echo '</pre>';exit;
      $database = DatabaseFactory::getFactory()->getConnection();

      try {
          
          //populate post data
          $price_net = FormaterModel::getNumberOnly(Request::post('price_net'));
          $customer = explode('---', Request::post('customer'));
          $customer_id = trim($customer[0]);
          $customer_name= trim($customer[1]);
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
                      //var_dump($bom_list);
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
          $sql = "INSERT INTO sales_order (transaction_number, status, sales_channel, price_net, customer_id, customer_name, creator_id, created_timestamp)
                            VALUES (:transaction_number, :status, :sales_channel, :price_net, :customer_id, :customer_name, :creator_id, :created_timestamp)";
          $insert = $database->prepare($sql);
          $insert->execute(array(
                          ':transaction_number' => $transaction_number,
                          ':status' => 1,
                            ':sales_channel' => 'consignment',
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

}
