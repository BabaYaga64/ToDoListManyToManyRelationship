<?php
class Task
{
        //create properties
        private $description;
        private $id;

        //construct objects
        //set id to null, allows it to know where to start
        function __construct($description, $id = null)
        {
            $this->description = $description;
            $this->id = $id;
        }

        //Sets and can modify the value of $description
        function setDescription($new_description)
        {
            $this->description = (string) $new_description;

        }

        //Gets the value of a private variable $description
        function getDescription()
        {
            return $this->description;
        }


        //Gets the value of the private variable $id, which referrs to the id number of the task in the database.
        function getId()
        {
            return $this->id;
        }

        //Sets and can modify value of $id, like generating a new id.
        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            $returned_id = $GLOBALS['DB']->query("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}') RETURNING id;");
            $result = $returned_id->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);

        }


        static function getAll()
        {
            $returned_all = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_all as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $new_task = new Task($description, $id);
                array_push($tasks, $new_task);
            }

            return $tasks;

        }


        function delete(){

            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id={$this->getId()}");


        }

        function update($new_description){

            $GLOBALS['DB']->exec("UPDATE tasks SET description ='{$new_description}' WHERE id={$this->getId()};");
            $this->setDescription($new_description);

        }

        static function find ($search_id)
        {
            $found_task = null;
            $tasks = Task::getAll();
            foreach($tasks as $task) {
                $task_id = $task->getId();
                if ($task_id == $search_id) {
                  $found_task = $task;
                }
            }
            return $found_task;
        }


        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM tasks *;");
        }

    }

    ?>
