<?php


function getMoreInfo($categories){
    foreach($categories as $index => $category){
        $categories[$index] = getMoreInfoSingle($category);
    }
    return $categories;
}


function getMoreInfoSingle($category){
    $category['category_anchor'] = categoryToAnchor($category['category_id'], $category['category_name']);
    return $category;
}

class Categories_model extends CI_Model{

    public function __construct(){
        parent::__construct();
        $this->load->helper('anchors');
    }

    /**
     * Generate category anchor
     * @param $id
     * @param $innerHTML
     * @return string
     */
    public function categoryToAnchor($id, $innerHTML){
        $link = "/products/search?category_id=$id";
        return "<a href='$link'>$innerHTML</a>";
    }


    public function getForSearch(){
        $this->db->select('category_id, category_name');
        $query = $this->db->get('Categories');
        return $query->result_array();
    }

    private function getGenderSql($gender){
        if($gender == "W") {
            $sql = "SELECT category_name, category_image, category_id
                    FROM Categories
                    WHERE UPPER(category_name) LIKE 'WOMEN%' OR UPPER(category_name) LIKE 'LAD%'
                    ORDER BY category_name";
            return $sql;
        }
        elseif($gender == "M"){
            $sql = "SELECT category_name, category_image, category_id
                    FROM Categories
                    WHERE UPPER(category_name) LIKE 'MEN%'
                    ORDER BY category_name";
            return $sql;
        }
        return "";
    }

    public function getGenderCategories($gender){
        $sql = $this->getGenderSql($gender);
        $query = $this->db->query($sql);
        $categories = $query->result_array();
        if($categories){
            $categories = getMoreInfo($categories);
        }
        return $categories;
    }
}

?>