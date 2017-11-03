<?php

class models_cartModel extends models_BaseModel
{
    public function getProductInfo($id)
    {
        $data = $this->db->select_full('SELECT id as product_id, fashion_name as product_name, image as product_image, price, discount, (price-discount) as new_price
                                                        FROM '. self::TBL_OPT .' 
                                                        WHERE id = '. $id, null, null, Database::ENCODE_HTML);

        return $data;
    }

    public function customerInfo($id){
        $data = $this->db->select_full('SELECT t1.id, t1.first_name, t1.last_name, t2.email, t3.phone, t4.address
                                FROM '. self::TBL_CLI . ' AS t1                      
                                LEFT JOIN ' . self::TBL_CLE .' AS t2 ON (t2.id_client = t1.id) AND t2.is_main = 1                      
                                LEFT JOIN ' . self::TBL_CLP .' AS t3 ON (t3.id_client = t1.id) AND t3.is_main = 1 
                                LEFT JOIN ' . self::TBL_CLA .' AS t4 ON (t4.id_client = t1.id) AND t4.is_main = 1 
                                WHERE t1.id = '.$id, null, Database::RETURN_DATA_ASSOC);
        return $data;
    }

    public function addOrder($order)
    {
        $ord = array(
            'id_client' => $order['customer']['id_client'],
            'id_tel'    => $order['customer']['id_tel'],
            'id_addr'   => $order['customer']['id_addr'],
            'comment'   => $order['customer']['comment'],
            'ttl_amount'=> $order['product']['price'],
            'date_add'  => date("Y-m-d H:i:s", time()),
            'status' => 1
        );
        $orderId = $this->db->insert(self::TBL_ORD,$ord, true);

        $ori = array(
            'order_id'   => $orderId,
            'product_id' => $order['product']['product_id'],
            'image'      => $order['product']['image'],
            'price'      => $order['product']['price'],
            'size'       => $order['product']['size']
        );
        $ori = $this->db->insert(self::TBL_ORI,$ori, true);

        return $ori;
    }

    public function checkCustomer( $email)
    {
        $res = $this->db->select_full('SELECT id_client FROM '. self::TBL_CLE." WHERE email ='". $email."'", null, Database::RETURN_DATA_ASSOC);
        return $res[0]['id_client'];
    }

    public function addNewClient($customer)
    {
        $arr = array(
            'first_name' => $customer['f_name'],
            'last_name'  => $customer['l_name'],
            'activation' => 1,
            'date_add'   => date("Y-m-d H:i:s", time())
        );
        $id_client = $this->db->insert(self::TBL_CLI,$arr, true);

        $email = array(
            'id_client'  => $id_client,
            'first_name' => $customer['f_name'],
            'email'      => $customer['email'],
            'is_main'    => 1
        );
        $this->db->insert(self::TBL_CLE, $email, true);

        $phone = array(
            'id_client'  => $id_client,
            'first_name' => $customer['f_name'],
            'phone'      => $customer['phone'],
            'is_main'    => 1
        );
        $id_tel = $this->db->insert(self::TBL_CLP, $phone, true);

        $address = array(
            'id_client' => $id_client,
            'address'   => $customer['address'],
            'is_main'    => 1
        );
        $id_ad = $this->db->insert(self::TBL_CLA, $address, true);

        $ccr= array(
            'id_client' => $id_client,
            'id_addr' => $id_ad,
            'id_tel'  => $id_tel,
            'is_main' => 1
        );
        $this->db->insert(self::TBL_CCR,$ccr, true);

        $res = array(
            'id_client' => $id_client,
            'id_tel'    => $id_tel,
            'id_addr'   => $id_ad
        );

        return $res;
    }

    public function addContactData($data)
    {
        $has_main_phone= $this->db->select_full('SELECT id FROM ' . self::TBL_CLP. " WHERE id_client = '".$data['id_client']."' AND is_main=1");
        $is_main_ph = (!empty ($has_main_phone))? 2 : 1;
        $phone = array(
            'id_client'  => $data['id_client'],
            'first_name' => $data['f_name'],
            'phone'      => $data['phone'], //might be changed adding area code
            'is_main'    => $is_main_ph
        );
        $inserted_phone = $this->db->insert(self::TBL_CLP,$phone, true);

        $id_tel= (!empty($inserted_phone))? $inserted_phone : NULL;

        $has_main_address= $this->db->select_full('SELECT id FROM '. self::TBL_CCR ." WHERE id_client = '".$data['id_client']."' AND is_main=1");
        $is_main_ad = (!empty ($has_main_address))? 2 : 1;
        $address = array(
        'id_client' => $data['id_client'],
        'address'   => $data['address'], // might be changed to $costumer->zip . ' ' . $costumer->address . ', ' . $costumer->city . ' ' . $costumer->county
        'is_main'   => $is_main_ad
        );
        $id_ad = $this->db->insert(self::TBL_CLA,$address, true);

        $ccr= array(
        'id_client' => $data['id_client'],
        'id_addr' => $id_ad,
        'id_tel'  => $id_tel,
        'is_main' => $is_main_ad
        );
        $this->db->insert(self::TBL_CCR,$ccr, true);

        $res = array(
            'id_tel'  => $id_tel,
            'id_addr' => $id_ad
        );
        return $res;
    }

}