<?php

class models_accountModel extends models_BaseModel
{
    public function register($data)
    {   //forming arrays and inserting data to different tables

        $arr = array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'password'   => $data['password'],
            'birth_date' => $data['birth_date'],
            'gender'     => $data['gender'],
            'activation' => md5($data['email'].time()),
            'date_add'   => date("Y-m-d H:i:s", time())
        );
        $id_client = $this->db->insert(self::TBL_CLI,$arr, true);

        $email = array(
            'id_client'  => $id_client,
            'first_name' => $data['first_name'],
            'email'      => $data['email'],
            'is_main'    => 1
        );
        $this->db->insert(self::TBL_CLE,$email,true);

        if (!empty($data['phone']))
        {
            $phone = array(
                'id_client' => $id_client,
                'first_name' => $data['first_name'],
                'phone' => $data['phone'], //might be changed adding area code
                'is_main' => 1
            );
            $this->db->insert(self::TBL_CLP,$phone, true);
        }
        return $id_client;
    }

    public function regLinkRefresh($link)
    {   //getting activation hash to refresh the link
        return $this->db->select_full('SELECT t1.first_name, t2.email 
                                      FROM '. self::TBL_CLI . ' AS t1 LEFT JOIN ' . self::TBL_CLE ." AS t2 ON (t2.id_client = t1.id) AND t2.is_main = 1 
                                      WHERE t1.activation = $link", null, Database::RETURN_DATA_ASSOC);
    }

    public function checkUser($email)
    {
        return $this->db->select_full("SELECT first_name, email FROM " . self::TBL_CLE. " WHERE email = '".$email."'",
                                        null, Database::RETURN_DATA_ASSOC);
    }

    public function atCheck($link)
    {
        return $act_time=$this->db->select_full('SELECT id, UNIX_TIMESTAMP(activation_time) AS at FROM '. self::TBL_CLI. " WHERE activation = '".$link."'", null, Database::RETURN_DATA_ASSOC);
    }

    public function emailconf($link)
    {
        $user = $this->db->select_full('SELECT id, first_name FROM '. self::TBL_CLI. " WHERE activation = '".$link."'", null, Database::RETURN_DATA_ASSOC);
        $arr = array(
            'activation'      => 1,
            'activation_time' => date("Y-m-d H:i:s", time())
        );
        //setting activation hash
        $this->db->update(self::TBL_CLI, $arr, "WHERE activation = '".$link."'");
        return $user;
    }

    public function signIn($email)
    {
        $res = $this->db->select_full("SELECT i.*, e.email FROM ". self::TBL_CLI . " AS i JOIN " . self::TBL_CLE . " AS e ON i.id=e.id_client WHERE email = '$email'",  null, Database::RETURN_DATA_ASSOC);
        return $res;
    }

    public function socialCheck($social_id)
    {
        return  $this->db->select_full('SELECT * FROM '. self::TBL_CSA ." WHERE soc_id = '".$social_id."'", null, Database::RETURN_DATA_ASSOC);
    }

    public function userData($id)
    {
        $data = $this->db->select_full('SELECT t1.id, t1.first_name, t1.last_name, t1.birth_date, YEAR(t1.birth_date) as byear, MONTH(t1.birth_date) as bmonth, DAY(t1.birth_date) as bday,t1.gender, t1.bonuses, t1.activation, t2.email, (SELECT GROUP_CONCAT(CONCAT_WS(\' ID=\', network, soc_id)) FROM `clients_soc_acc` WHERE id_client='.$id.')as soc_id
                                FROM '. self::TBL_CLI . ' AS t1                      
                                LEFT JOIN ' . self::TBL_CLE .' AS t2 ON (t2.id_client = t1.id) AND t2.is_main = 1                      
                                LEFT JOIN ' . self::TBL_CSA .' AS t3 ON (t3.id_client = t1.id)
                                WHERE t1.id = '.$id.'
                                GROUP BY id',
                                null, Database::RETURN_DATA_ASSOC);

        $data[0]['soc_id']=explode(',',$data[0]['soc_id']);
        return $data;
    }

    public function userContactData($id)
    {
        $data['main'] = $this->db->select_full('SELECT t1.id as relation_id, t1.id_addr, t2.address, t1.id_tel, t3.phone, t1.is_main
                                        FROM '. self::TBL_CCR . ' AS t1
                                        LEFT JOIN ' . self::TBL_CLA .' AS t2 ON (t1.id_addr=t2.id)
                                        LEFT JOIN ' . self::TBL_CLP .' AS t3 ON (t1.id_tel=t3.id)
                                        WHERE t1.id_client ='.$id.' and t1.is_main = 1',
                                        null, Database::RETURN_DATA_ASSOC);
        $data['additional'] = $this->db->select_full('SELECT t1.id as relation_id, t1.id_addr, t2.address, t1.id_tel, t3.phone, t1.is_main
                                        FROM '. self::TBL_CCR . ' AS t1
                                        LEFT JOIN ' . self::TBL_CLA .' AS t2 ON (t1.id_addr=t2.id)
                                        LEFT JOIN ' . self::TBL_CLP .' AS t3 ON (t1.id_tel=t3.id)
                                        WHERE t1.id_client = '.$id.' and t1.is_main !=1
                                        ORDER BY t1.id',
                                        null, Database::RETURN_DATA_ASSOC);
        return $data;
    }

    public function userOrderData($id){
        $data = $this->db->select_full('SELECT t1.id as order_id, DATE_FORMAT(t1.date_add,\'%M\') as month, YEAR(t1.date_add) as year, DATE_FORMAT(t1.date_add, "%e.%m.%Y") as date_add, t1.ttl_amount as price, t2.image
                                FROM '. self::TBL_ORD . ' AS t1                                           
                                LEFT JOIN ' . self::TBL_ORI .' AS t2 ON (t1.id = t2.order_id)
                                WHERE t1.id_client = '.$id.'
                                ORDER BY date_add ASC',
                                null, Database::RETURN_DATA_ASSOC);
        return $data;
    }

    public function socialDataToUser($data)
    {   //forming arrays and updating accounts of existing clients
        $arr = array(
            'id'     => $data['id_client'],
            'avatar' => $data['avatar'],
            'activation' => 1,
            'activation_time' => date("Y-m-d H:i:s", time())
        );
        $this->db->update(self::TBL_CLI, $arr, 'WHERE id=' . $arr['id']);

        $social=array(
            'id_client' => $data['id_client'],
            'network'   => $data['network'],
            'soc_id'    => $data['soc_id'],
        );
        $this->db->insert(self::TBL_CSA, $social, true);
        return true;
    }

    public function addSocialUser($data)
    {   //forming arrays and inserting data to different tables

        $arr = array(
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'gender'      => $data['gender'],
            'avatar'      => $data['avatar'],
            'activation' => 1,
            'date_add'    => date("Y-m-d H:i:s", time())
        );
        $id_client = $this->db->insert(self::TBL_CLI,$arr, true);

        $social=array(
            'id_client'  => $id_client,
            'network'    => $data['network'],
            'soc_id'     => $data['soc_id'],
        );
        $this->db->insert(self::TBL_CSA,$social, true);

        if (isset($data['email']))
        {
            $email = array(
                'id_client'  => $id_client,
                'first_name' => $data['first_name'],
                'email'      => $data['email'],
                'is_main'    => 1
            );
            $this->db->insert(self::TBL_CLE,$email, true);
        }
        return $id_client;
    }

    public function passCheck($id)
    {
        return $this->db->select_full("SELECT * FROM ". self::TBL_CLI . " WHERE id = $id",null, Database::RETURN_DATA_ASSOC);
    }

    public function passwordChange($pass,$id)
    {
        $arr = array(
            'id'       => $id,
            'password' => $pass);
        $this->db->update(self::TBL_CLI, $arr, 'WHERE id=' . $arr['id']);
        return true;
    }

    public function activationReset($email, $link)
    {
        $user = $this->db->select_full('SELECT id_client, first_name FROM '. self::TBL_CLE ." WHERE email = '".$email."'",null, Database::RETURN_DATA_ASSOC);
        $arr = array(
            'id'=> $user[0]['id_client'],
            'activation' => $link,
            'activation_time' => date("Y-m-d H:i:s", time())
        );
        //reseting activation hash as to "not confirmed activation"
        $this->db->update(self::TBL_CLI, $arr,'WHERE id=' . $arr['id']);
        return $user;
    }

    public function editInfo($data)
    {
        $id = $data['id'];
        $arr = array(
            'id'         => $id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'gender'     => $data['gender'],
        );
        $this->db->update(self::TBL_CLI, $arr, 'WHERE id=' . $arr['id']);

        $user_has_email = $this->db->select_full('SELECT email FROM '. self::TBL_CLE. "WHERE id_client = $id",null, Database::RETURN_DATA_ASSOC);
        if (!empty($user_has_email))
        {
            $email = array(
                'first_name' => $data['first_name'],
                'email'      => $data['email']
            );
            $this->db->update(self::TBL_CLE,$email,"WHERE id_client=$id AND is_main=1" );
        } else {
            $email = array(
                'id_client' => $id,
                'first_name' => $data['first_name'],
                'email'      => $data['email'],
                'is_main' => 1
            );
            $this->db->insert(self::TBL_CLE,$email, true);
        }

        return true;
    }

    public function editContactData($data)
    {
        $id_addr = $data['id_addr'];
        if (!empty($data['phone']))
        {
            if ($data['id_tel'] != 0)
            {
                $phone = array(
                    'id'    => $data['id_tel'],
                    'phone' => $data['phone'] //might be changed adding area code
                );
                $this->db->update(self::TBL_CLP, $phone, 'WHERE id=' . $phone['id']);
            } else {
                $phone = array(
                    'id_client'  => $data['id'],
                    'first_name' => $data['first_name'],
                    'is_main'    => $data['is_main'],
                    'phone'      => $data['phone'] //might be changed adding area code
                );
                $inserted_phone = $this->db->insert(self::TBL_CLP, $phone, true);

                $ph_id=array('id_tel'=> $inserted_phone);
                $this->db->update(self::TBL_CCR,$ph_id, "WHERE id_addr = $id_addr");
            }
        }
        $address = array(
            'id' => $id_addr,
            'address' => $data['address'] // might be changed to $costumer->zip . ' ' . $costumer->address . ', ' . $costumer->city . ' ' . $costumer->county
        );
        $this->db->update(self::TBL_CLA,$address, 'WHERE id=' . $address['id']);
        return true;
    }

    public function changeDefaultAddr($data){

        $main_a = $data ['curr_main_address'];
        $main_t = $data ['curr_main_tel'];
        $address = array('is_main' => 2); // might be changed to $costumer->zip . ' ' . $costumer->address . ', ' . $costumer->city . ' ' . $costumer->county
        $tel = array('is_main' => 2);
        $this->db->update(self::TBL_CCR, $address, "WHERE id_addr = $main_a");
        $this->db->update(self::TBL_CLA, $address, "WHERE id = $main_a");
        $this->db->update(self::TBL_CLP, $tel, "WHERE id = $main_t");

        $new_main_addr = $data['new_def_address'];
        $new_main_tel = $data['new_def_tel'];

        $address = array('is_main' => 1); // might be changed to $costumer->zip . ' ' . $costumer->address . ', ' . $costumer->city . ' ' . $costumer->county
        $tel = array('is_main' => 1);
        $this->db->update(self::TBL_CCR, $address, "WHERE id_addr = $new_main_addr");
        $this->db->update(self::TBL_CLA, $address, "WHERE id = $new_main_addr");
        $this->db->update(self::TBL_CLP, $tel, "WHERE id = $new_main_tel");
        return true;
    }

    public function addContactData($data)
    {
        if (!empty($data['phone']))
        {
            $has_main_phone= $this->db->select_full('SELECT id FROM ' . self::TBL_CCR. " WHERE id_client = '".$data['id']."' AND is_main=1");
            $is_main_ph = (!empty ($has_main_phone))? 2 : 1;
            $phone = array(
                'id_client'  => $data['id'],
                'first_name' => $data['first_name'],
                'phone'      => $data['phone'], //might be changed adding area code
                'is_main'    => $is_main_ph
            );
            $inserted_phone = $this->db->insert(self::TBL_CLP,$phone, true);
        }
        $id_tel= (!empty($inserted_phone))? $inserted_phone : NULL;

        $has_main_address= $this->db->select_full('SELECT id FROM '. self::TBL_CCR ." WHERE id_client = '".$data['id']."' AND is_main=1");
        $is_main_ad = (!empty ($has_main_address))? 2 : 1;
        $address = array(
            'id_client' => $data['id'],
            'address'   => $data['address'], // might be changed to $costumer->zip . ' ' . $costumer->address . ', ' . $costumer->city . ' ' . $costumer->county
            'is_main'   => $is_main_ad
        );
        $id_ad = $this->db->insert(self::TBL_CLA,$address, true);

        $ccr= array(
            'id_client' => $data['id'],
            'id_addr' => $id_ad,
            'id_tel'  => $id_tel,
            'is_main' => $is_main_ad
        );
        $this->db->insert(self::TBL_CCR,$ccr, true);
        return true;
    }

    public function deleteContactData($data)
    {
        $id_addr = $data['id_addr'];
        $id_tel = $data['id_tel'];
        $this->db->delete(self::TBL_CLA, "WHERE id = '".$id_addr."'");
        if ($id_tel != 0)
            $this->db->delete(self::TBL_CLP, "WHERE id = '".$id_tel."'");
        return true;
    }

    public function deleteUser($id)
    {
        $this->db->delete(self::TBL_CLI, 'WHERE id='. $id);
        return true;
    }
}