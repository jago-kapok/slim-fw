<?php

/*
* POS (Point of Sales) aka Kasir
*/
class PaymentController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // special authentication check for the entire controller: Note the check-ADMIN-authentication!
        // All methods inside this controller are only accessible for admins (= users that have role type 7)
        Auth::checkAuthentication();
    }


    public function insertPayment()
    {
        $so_number = urldecode(Request::get('so_number'));
        for ($i=1; $i < 10; $i++) { 
            if (!empty(Request::post('value_' . $i)) AND !empty(Request::post('date_' . $i)) AND !empty(Request::post('type_' . $i)))
            {
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_code' => $so_number,
                        'transaction_type' => 'sales order',
                        'status' => -1,
                        'credit' => FormaterModel::getNumberOnly(Request::post('value_' . $i)),
                        'payment_type' => Request::post('type_' . $i),
                        'payment_due_date' => Request::post('date_' . $i),
                        'note' => Request::post('note_' . $i),
                        'creator_id'    => SESSION::get('uid')
                    );
                //Debuger::jam($insert);
                GenericModel::insert('payment_transaction', $insert);
            }
        }
        
    }


}