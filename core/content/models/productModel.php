<?php
class models_productModel extends models_BaseModel
{
    public function getProductsList()
    {
        $data = $this->db->select_full('SELECT id as brand_id, name as brand_name, image as brand_image FROM '. self::TBL_PRD,
                                        null, Database::RETURN_DATA_ASSOC);
        $data = $this->productsOfBrand($data);
        return $data;
    }
    public function productsOfBrand($data)
    {
        foreach ($data as $k=>$v) {
            $data[$k]['products'] = $this->db->select_full('SELECT id as product_id, fashion_name as product_name, image as product_image, price, discount, size, (price-discount) as new_price
                                                        FROM ' . self::TBL_OPT . ' 
                                                        WHERE brand_id = ' . $v['brand_id'], null, null, Database::ENCODE_HTML);
        }

        return $data;
    }

    public function getProductInfo($id)
    {
        $data = $this->db->select_full('SELECT id as product_id, fashion_name as product_name, image as product_image, size, price, discount, (price-discount) as new_price
                                                        FROM '. self::TBL_OPT .' 
                                                        WHERE id ='.$id, null, null, Database::ENCODE_HTML);

        return $data;
    }

}