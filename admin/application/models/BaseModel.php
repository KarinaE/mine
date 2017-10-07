<?php
defined('_ACCESS') or die;

abstract class models_BaseModel
{    
    const TBL_CATEGORIES    = 'categories';

    const TBL_CLIENTS_INFO      = 'clients_info';
    const TBL_CLIENTS_PHONES    = 'clients_phones';
    const TBL_CLIENTS_EMAILS    = 'clients_emails';
    const TBL_CLIENTS_ADDRESS   = 'clients_address';
    const TBL_CLIENTS_SOC_ACC   = 'clients_soc_acc';

    const TBL_CLOTH_TYPES   = 'cloth_types';
    const TBL_CLOTH_SIZES   = 'cloth_sizes';
    const TBL_CLOTH_OPTIONS = 'cloth_size_options';

    const TBL_COMMENTS      = 'comments';
    const TBL_COMPONENTS    = 'components';
    const TBL_CONTENT       = 'content';
    const TBL_CURRENCY      = 'currency';
    const TBL_IMAGES        = 'images';   
    const TBL_MENU          = 'menu';
    const TBL_MENU_NAME     = 'menu_name';
    const TBL_MENU_REL      = 'menu_user_types_relations';
    const TBL_MODULES       = 'modules';
    const TBL_MOD_ITEMS     = 'modules_items';
    const TBL_LANGUAGE      = 'language';
    const TBL_LOOKBOOK      = 'lookbook';
    const TBL_OPTIONS       = 'options';

    const TBL_ORDERS        = 'orders';
    const TBL_ORDER_ITEMS   = 'order_items';
    const TBL_FAVORITES     = 'favorites';

    const TBL_PRODUCTS      = 'products';
    const TBL_PARAMS        = 'params';
    const TBL_PARAMETERS    = 'product_options';
    const TBL_PROD_PAR_ACT  = 'product_options_active';
    const TBL_PROD_REMAIN   = 'product_quantity';
    const TBL_PROD_WARDROBE = 'product_wardrobe_images';

    const TBL_SUPPLIERS     = 'suppliers';
    const TBL_SUPP_SIZES    = 'suppliers_sizes';
    const TBL_SUPP_VALUES   = 'suppliers_sizes_values';

    const TBL_TEMPLATES     = 'templates';
    const TBL_USERS         = 'users';
    const TBL_UTYPES        = 'user_types';
    const TBL_UTYPES_REL    = 'user_types_rel';
    
    protected $id;
    protected $db;
    protected $uinfo; // user info (front-end)

    public function __construct($id = null)
    {
        $this->db = Database::instance();
        $this->notices = models_helpers_Notices::instance();
        $this->message = models_helpers_Language::instance()->getPack('Messages');
        $this->session = Session::instance();
        $this->id = $id;
        
        $this->uinfo = models_helpers_Access::getAuthInfoAdmin();
    }
    
    public function getProjectId()
    {
        return Request::instance()->getPath();
    }
    
    /** 
    * Db tables mapper
    * @param string $const_name
    * @return string correct table for project
    */
    public static function getTbl($const_name)
    {
        $prj = Request::instance()->getProject();
        
        if ($prj)
        {
            $prj = strtoupper($prj);
            $var = 'TBL_' . $prj . '_' . preg_replace('/^TBL_/', '', $const_name);
            
            $res = eval("return defined('static::$var') ? self::$var : '';");
            
            if (!$res)
            {
                $res = eval("return defined('static::$const_name') ? self::$const_name : '';");
            }
            
            return $res;
            } 
    }
    
    final public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    final public function getId()
    {
        return $this->id;
    }
    
    public function addImage($name,$source,$path,$base_path,$base_path_small='',$size='',$newname='') 
    {
        if (empty($name))
            exit($this->message['base_error_noimage']); 
        else
        {
            // путь для большой картинки
            $bigpath  = $path.models_helpers_Url::getDomain().$base_path;
            // путь для маленькой картинки 
            $minipath = $path.models_helpers_Url::getDomain().$base_path_small; 
           	// если картинка в нужном формате 
            if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$name))
            {	
                $filename = $name; // имя файла
                // размер картинк
                
                $infoimg = getimagesize($source);
                $w = $infoimg[0]; // ширина
                $h = $infoimg[1]; // высота      
                $target = $bigpath.$name;
                // перемещение загруженного файла в нужное место (Большая картинка)
                move_uploaded_file($source, $target);  
                
                // создание картинки из gif
               	if(preg_match('/[.](GIF)|(gif)$/', $filename)) 
                {
                	if(!empty($base_path)) $im = imagecreatefromgif($bigpath.$filename) ; 
               		$imm = imagecreatefromgif($bigpath.$filename);
                    $format = 'gif';
               	} 
                // создание картинки из png
               	if(preg_match('/[.](PNG)|(png)$/', $filename)) 
                {
                	if(!empty($base_path)) $im = imagecreatefrompng($bigpath.$filename) ;
                    $imm = imagecreatefrompng($bigpath.$filename);
                    $format = 'png';
               	} 
                // создание картинки из jpeg	
               	if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) 
                {
              		if(!empty($base_path)) $im = imagecreatefromjpeg($target);
                    $imm = imagecreatefromjpeg($target);
                    $format = 'jpg';
               	} 
                // размеры миниатюры	 
                $wm=$size;
                // ширина изображения image в пикселях
                $w_src = imagesx($imm);
                // высота изображения image в пикселях  
                $h_src = imagesy($imm);  
                // создает новове изображение (Большое)   
                if(!empty($base_path)) $dest = imagecreatetruecolor($w,$h); 
                // создает новове изображение (Миниатюра)
                if ($base_path_small)
                    $minidest = empty($height) ? imagecreatetruecolor($wm,$wm) : imagecreatetruecolor($wm,$height) ; 
                // сохранение прозрачности изображения, если она присутствует
                if(!empty($base_path)) 
                    imagealphablending($dest, false);
                imagealphablending($dest, false); 
                if(!empty($base_path)) 
                    imagesavealpha($dest, true);
                if ($base_path_small)
                    imagesavealpha($minidest, true);

                if ($w>$h)
                {  
                    // подгон формата если картинка горизонтальная
                    if(!empty($base_path)) imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w, $w);
                         
                }  
                if ($w<$h)
                {  
                    // подгон формата если картинка вертикальная
                    if(!empty($base_path)) imagecopyresampled($dest, $im, 0, 0, 0, 0, $h, $h, $h, $h); 
                }         
                if ($w==$h)
                { 
                    // подгон формата если картинка квадратная
                    if(!empty($base_path)) imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w, $w); 
                }
                // берем текущее время или уже указанное имя  
                $date = $newname ? $newname : time(); 
                // создаем файл большой картинки
                if(!empty($base_path)) imagejpeg($dest, $bigpath.$date.'.'.$format,100);
                // путь большой картинки 
                if(!empty($base_path)) $pathbig = $base_path;
                // большая картинка 
                if(!empty($base_path)) $img["image"] = $pathbig.$date.'.'.$format; 
                
                if ($base_path_small)
                {
                    $img["smallimg"] = $this->createMini($w,$h,$size,$w_src,$h_src,$minipath,$base_path_small,$imm,$date,$wm,$format); // маленькая картинка
                
                    $this->createMini($w,$h,$size,$w_src,$h_src,$minipath,$base_path_small,$imm,$date,230,$format);
                    $this->createMini($w,$h,$size,$w_src,$h_src,$minipath,$base_path_small,$imm,$date,125,$format);
                    $this->createMini($w,$h,$size,$w_src,$h_src,$minipath,$base_path_small,$imm,$date,75,$format);    
                }
                
                $delfull = $bigpath.$filename;
                // удаляем лишнее 
                unlink ($delfull); 
            }
            else        
                return $this->message['base_error_format']; // эрор если картинка в неправильном формате

            return $img;
        }
    }// функция добавления картинки в базу
    
    // создание миниатюры картинки
    private function createMini($w,$h,$size,$w_src,$h_src,$minipath,$base_path_small,$imm,$date,$wm,$format){
               
        // создает новове изображение (Миниатюра)
        $minidest = imagecreatetruecolor($wm,$h/($w/$wm));  
        
        if ($w>$h){  
            // подгон формата если картинка горизонтальная
            imagecopyresampled($minidest, $imm, 0, 0, 0, 0, $wm,$h/($w/$wm),$w,$h); 
                 
        }  
        if ($w<$h){  
            // подгон формата если картинка вертикальная
            imagecopyresampled($minidest, $imm,  0, 0, 0, 0, $wm, $h_src/($w_src/$wm), $w_src, $h_src);
        }         
        if ($w==$h){ 
            // подгон формата если картинка квадратная
            imagecopyresampled($minidest, $imm, 0, 0, 0, 0, $wm, $wm, $w_src, $w_src); 
        }

        imagejpeg($minidest,$wm >= 450 ? $minipath.$date.'.'.$format : $minipath.$date."-$wm.".$format ,100); // создаем файл миниатюры
        
        return $base_path_small.$date.'.'.$format;     
        
    }
    
    public function deleteImage()
    {
        $res = $this->db->select(self::TBL_IMAGES, 'image,smallimg','WHERE id=' . $this->id,'','RETURN_DATA_ARR');
        // check if previous image exists
        if(!is_array($res))
            return false;
            
        // base root to images
        $root = $_SERVER['DOCUMENT_ROOT'].models_helpers_Url::getDomain();

        //deleting all variations of images
        $this->delete_img($root,substr($res[0]['image'],1));
        $this->delete_img($root,substr($res[0]['smallimg'],1));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-75'.substr($res[0]['smallimg'],-4));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-125'.substr($res[0]['smallimg'],-4));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-230'.substr($res[0]['smallimg'],-4));
        
        return $this->db->delete(self::TBL_IMAGES, 'WHERE id=' . $this->id);
    }
    
    // deleting image from server
    public function delete_img($path,$url) 
    {
        unlink($path.$url);
    }
    
}
?>