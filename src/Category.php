<?php
    class Category
    {
        //create properties
        private $name;
        private $id;

        //construct gets called when objects are instantiated
        //set id to a default to null, allows it to know where to start
        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        //Sets and can modify the value of $name, a private property
        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        //Gets the value of a private variable $name, a private property
        function getName()
        {
            return $this->name;
        }

        //Gets the value of the private variable $id out of the object.
        function getId()
        {
            return $this->id;
        }

        //Sets and can modify value of $id, like generating a new id.
        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save(){
            $returned_id = $GLOBALS['DB']->query("INSERT INTO categories (name) VALUES ('{$this->getName()}') Returning Id;");
            $result=$returned_id->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);


        }

        static function getAll(){

            $returned_categories= $GLOBALS['DB']->query("SELECT * FROM categories;");
            $array_categories= array();
            foreach($returned_categories as $category){

                $name = $category['name'];
                $id = $category['id'];
                $new_category= new Category ($name,$id);
                array_push($array_categories,$new_category);

            }
            return $array_categories;

        }



        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

}


?>
