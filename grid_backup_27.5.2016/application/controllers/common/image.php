<?php
class Image extends CI_Controller {

 public function index($width, $height, $image_path)
    {   
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/'.$image_path;
        $config['dynamic_output'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;

        $this->load->library('image_lib', $config); 
        $this->image_lib->initialize($config);
        echo $this->image_lib->resize();
    }
}

?>