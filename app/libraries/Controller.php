<?php
    class Controller{

        // to load the model (model is used to sql queries)
        public function model($model) {
            require_once APPROOT.'/models/'.$model.'.php';
            
            //instantiate the model and pass it to the controller member variable
            return new $model();
        }

        // to load the view (files containing output html)
        public function view($view,$data = []) {
            if(file_exists(APPROOT.'/views/'.$view.'.php')){
                require_once APPROOT.'/views/'.$view.'.php';
            }
            else{
                die('Corresponding view does not exist');   
            }
        }
    }
        
?>