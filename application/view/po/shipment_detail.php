<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
          <tbody>
            <tr class="info">
              <td colspan="4" class="text-center">
                Cara Pembayaran
              </td>
            </tr>
            
            <tr>
                <td>Payment Term:</td>
                <td colspan="3">
                    <?php echo $this->shipment_data->payment_term; ?>
                </td>
            </tr>

            <tr class="info">
                <td colspan="4" class="text-center">Cara Pengiriman</td>
            </tr>

            <tr>
                <td>Biaya Pengiriman:</td>
                <td>
                	<?php echo number_format($this->shipment_data->purchase_price, 0) ; ?>
                </td>
                <td>Freight Vendor</td>
                <td>
                	<?php echo $this->shipment_data->contact_name; ?>
                </td>
            </tr>

            <tr>
                <td>Freight Term:</td>
                <td>
                	<?php echo $this->shipment_data->freight_term; ?>
                </td>
                <td>Freight Payment:</td>
                <td>
                	<?php echo $this->shipment_data->freight_payment; ?>
                </td>
            </tr>

            <tr>
                <td>Delivery Time:</td>
                <td>
                    <?php echo $this->shipment_data->delivery_time; ?>
                </td>
                <td>Delivery Point:</td>
                <td>
                	<?php echo $this->shipment_data->ship_via; ?>
                </td>
            </tr>
          </tbody>
</table>