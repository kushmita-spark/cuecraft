<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
    

    
    public function getUserlogin($admin_email, $admin_password) {
        $admin_password = md5($admin_password);
        $this->db->select('id,admin_user_name,admin_email,phone_number,profile_image,role,address');
        $this->db->where('admin_email', $admin_email);
        $this->db->where('admin_password', $admin_password);
        $this->db->from('admin_login');
        $stor = $this->db->get();
        return $stor;
    }
    












 /* old Api start */   
    public function getHomeCategory() {
        $this->db->select('id,android_image,name');
        $this->db->from('category');
        $this->db->where('show_in_home', '1');
        $this->db->or_where('show_in_nav', '1');
        $num = $this->db->get();
        $arr=array();
        
        $i=0;
        
        foreach($num->result() as $row){
            $arr2=array();
            
            $arr2['id']=$row->id;
            $arr2['image']=base_url().'uploads/category/'.$row->android_image;
            $arr2['name']=$row->name;
            $arr[$i]=(Object) $arr2;
            $i++;
        }
        return  $arr;
    }
    public function getSubCategory($id) {
        $this->db->select('id,android_image,name');
        $this->db->from('category');
        $this->db->where('parent', $id);
        $num = $this->db->get();
        $arr=array();
        
        $i=0;
        
        foreach($num->result() as $row){
            $arr2=array();
            
            $arr2['id']=$row->id;
            $arr2['image']=base_url().'uploads/category/'.$row->android_image;
            $arr2['name']=$row->name;
            $arr[$i]=(Object) $arr2;
            $i++;
        }
        return  $arr;
    }
    
    
    
    
    
    /************************************************/

    public function registration($arr) {
        $this->db->insert('customers', $arr);
    }
    public function registerTempUser($arr) {
        $this->db->insert('temp_users', $arr);
    }

    public function loginByMobile($mob) {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('phone_number', $mob);
        $num = $this->db->get();
        return $num;
    }

    public function updateotp($mob, $arr) {
        $this->db->where('phone_number', $mob);
        $this->db->update('customers', $arr);
    }

    public function verifyotp($otp, $userid) {
        //print_r($_POST);die;
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('otp', $otp);
        $this->db->where('id', $userid);
        $num = $this->db->get();
        return $num;
    }
    public function verifyOtpForRegister($otp, $userid) {
        //print_r($_POST);die;
        $this->db->select('*');
        $this->db->from('temp_users');
        $this->db->where('otp', $otp);
        $this->db->where('id', $userid);
        $num = $this->db->get();
        return $num;
    }

    public function user_details($id) {
        //print_r($_POST);die;
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('id', $id);
        $num = $this->db->get();
        return $num->row();
    }
    public function getUserShippingAddress($id) {
        //print_r($_POST);die;
        $this->db->select('*');
        $this->db->from('address');
        $this->db->where('user_id', $id);
        $this->db->where('type','2');
        $num = $this->db->get();
        return $num->row();
    }
    public function getAllowedPincodes() {
        //print_r($_POST);die;
        $this->db->select('*');
        $this->db->from('allowed_pincode');
        $num = $this->db->get();
        return $num->result();
    }
    public function saveOrder($arr) {
        $this->db->insert('orders', $arr);
    }
    public function removeCartItem($cid) {
        $this->db->where('customer_id', $cid);
        $this->db->delete('cart');
    }

    public function banner() {
        $this->db->select('*');
        $this->db->from('banner');
        $this->db->where('status', '1');
        $num = $this->db->get();
        return $num->result();
    }

    public function testing_monial() {
        $this->db->select('*');
        $this->db->from('testing_monial');
        $num = $this->db->get();
        return $num->result();
    }

    public function getcategoryById($id,$show) {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where('parent', $id);
        $this->db->where('show_in_nav', $show);
        $num = $this->db->get();
        return $num->result();
    }
    public function getCategoryForTabs($cts) {
        $this->db->select('*');
        $this->db->from('category');
        foreach($cts as $row){
        $this->db->or_where('id', $row);
        }
        $num = $this->db->get();
        return $num->result();
    }
    public function getsearchcategories() {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where('type', '0');
        $num = $this->db->get();
        return $num->result();
    }
    
    public function getCategoryDetailById($id) {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where('id', $id);
       
        $num = $this->db->get();
        return $num->row();
    }

    public function getCatecodeByCategoryId($catid) {
      
        $this->db->select('marg_category');
        $this->db->from('category');
        $this->db->where('id', $catid);
        $num = $this->db->get();
        return $num->row()->marg_category;
    }
    public function getProductByCategoryId($catid, $limit, $offset) {
        $catcode=$this->getCatecodeByCategoryId($catid);
        $this->db->limit($limit, $offset);
        $this->db->select('products.*');
        $this->db->from('products');
        $this->db->where('catcode', $catcode);
        $this->db->where("status",'1'); 
        $this->db->where("stock!=",'0.000');
        $num = $this->db->get();
        return $num->result();
    }
    public function gerProductCodeById($id) {
        
        
        $this->db->select('code');
        $this->db->from('products');
        $this->db->where('id', $id);
       
        $num = $this->db->get();
        return $num->row()->code;
    }
    public function getProductsForTab($catcode) {
       
        $this->db->limit(12);
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where("status",'1'); 
        $this->db->where("stock!=",'0.000');
        $num = $this->db->get();
        return $num->result();
    }

    public function getProductCountByCategoryId($catid) {
         $catcode=$this->getCatecodeByCategoryId($catid);
        $this->db->select('*');
        $this->db->from('products');
         $this->db->where('catcode', $catcode);
         $this->db->where("status",'1'); 
        $this->db->where("stock!=",'0.000');
        $num = $this->db->get();
        return $num->num_rows();
    }

    public function productDetailByProductId($proid) {
        $this->db->select('products.*');
       // $this->db->select('product_category.*,products.product_img,products.MRP,product_img,products.name,category.description');
        $this->db->from('products');
        //$this->db->join('products', 'product_category.product_id=products.id', 'left');
        //$this->db->join('category', 'product_category.category_id=category.id','left');
        $this->db->where('id', $proid);
        $num = $this->db->get();
        return $num->row();
    }
    public function searchProductCount($catid,$seatchtext) {
         
        $this->db->select('*');
        $this->db->from('products');
        if($catid!=0){
            $catcode=$this->getCatecodeByCategoryId($catid);
            $this->db->where('catcode', $catcode);
            
        }
         $this->db->like('name', '%'.$seatchtext.'%');
        $this->db->where("status",'1'); 
        $this->db->where("stock!=",'0.000');
        $num = $this->db->get();
        return $num->num_rows();
    }
    public function searchProduct($catid,$seatchtext, $limit, $offset) {
        
        $this->db->limit($limit, $offset);
        $this->db->select('products.*');
        $this->db->from('products');
        if($catid!=0){
            $catcode=$this->getCatecodeByCategoryId($catid);
            $this->db->where('catcode', $catcode);
            
        }
        $this->db->like('name', $seatchtext);
        $this->db->where("status",'1'); 
        $this->db->where("stock!=",'0.000');
        $num = $this->db->get();
        //echo $this->db->last_query(); die;
        return $num->result();
    }

    

    public function cartdata($productid, $cid) {
        $this->db->select('*');
        $this->db->from('cart');
        $this->db->where('product_id', $productid);
        $this->db->where('customer_id', $cid);
        $num = $this->db->get();
        return $num;
    }

    public function qtyupdate($id, $arr) {
        $this->db->where('id', $id);
        $this->db->update('cart', $arr);
    }

    public function add_to_cart($save) {
        $this->db->insert('cart', $save);
    }
    public function addToWishlist($save) {
        //print_r($save);
        $this->db->select('*');
        $this->db->from('wishlist');
        $this->db->where('userid',$save['customer_id']);
        $this->db->where('product_id',$save['product_id']);
        //echo count($this->db->get()->result());die;
        if(!$this->db->get()->num_rows()){
             $this->db->insert('wishlist', $save);
        }
        //echo $this->db->last_query(); die;
       
    }
    public function removeFromCart($cartid) {
        $this->db->where('id', $cartid);
        $this->db->delete('cart');
        
    }
    public function removeFromWishlist($wishlistid) {
        $this->db->where('id', $wishlistid);
        $this->db->delete('wishlist');
    }
    public function updateCartItemQty($cartid,$qty) {
        $arr=array(
            'quantity'=>$qty
        );
        $this->db->where('id', $cartid);
        $this->db->update('cart',$arr);
    }

    public function get_cart_item($cid) {
        $this->db->select('cart.*,products.name,products.product_img,products.MRP,products.Rate');
        $this->db->from('cart');
        $this->db->join('products', 'cart.product_id=products.code','left');
        $this->db->where('customer_id', $cid);
        $num = $this->db->get();
        return $num->result();
    }
    public function getOrdersList($id) {
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('userid', $id);
        $num = $this->db->get();
        return $num->result();
    }
    public function getWishlist($id) {
        $this->db->select('wishlist.*,products.name');
        $this->db->from('wishlist');
        $this->db->join('products','wishlist.product_id=products.code');
        $this->db->where('userid', $id);
        $num = $this->db->get();
        return $num->result();
    }
    
    public function insertOrderData($payload) {
        $ch = curl_init("https://wservices.margcompusoft.com/api/eOnlineData/InsertOrderDetail");                                                     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $data = substr(trim(gzinflate(base64_decode($response))), 3);
        $res = json_decode($data);
       // echo '<pre>';
        //print_r($res);die;
        return $res;
        
    }
    /* old Api start */ 

}
