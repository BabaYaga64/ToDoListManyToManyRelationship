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


        function update($new_name) {

            $GLOBALS['DB']->exec("UPDATE categories SET description = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories * WHERE id = {$this->getId()};");
        }

        function addTask($task)
        {

        $GLOBALS['DB']->query("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()} , {$task->getId()});");

        }

        function getTasks()
        {
            $query = $GLOBALS['DB']->query("SELECT task_id FROM categories_tasks WHERE category_id = {$this->getId()};");
            $task_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $tasks = array();
            foreach($task_ids as $id) {
                $task_id = $id['task_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE id = {$task_id};");
                $returned_task  = $result->fetchAll(PDO::FETCH_ASSOC);

                $description = $returned_task[0]['description'];
                $id = $returned_task[0]['id'];
                $new_task = new Task($description, $id);
                array_push($tasks, $new_task);
            }

            return $tasks;        }


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
        static function find($search_id){
            $found_category= null;
            $allCategories= Category::getAll();
            foreach ($allCategories as $category){
            $category_id= $category->getId();
            if ($category_id == $search_id){

                $found_category= $category;
            }
        }
            return $found_category;

        }


        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

}


?>
