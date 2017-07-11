<?php
    class Task
    {
        private $description;
        private $category_id;
        private $due_date;
        private $id;

        function __construct($description, $category_id, $due_date, $id = null)
        {
            $this->description = $description;
            $this->category_id = $category_id;
            $this->due_date = $due_date;
            $this->id = $id;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = (string) $new_due_date;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function save()
        {
            $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id, due_date) VALUES ('{$this->getDescription()}', {$this->getCategoryId()},'{$this->getDueDate()}')");
            if ($executed) {
                $this->id = $GLOBALS['DB']->lastInsertId();
                return true;
            } else {
                return false;
            }
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $category_id = $task['category_id'];
                $due_date = $task['due_date'];
                $id = $task['id'];
                $new_task = new Task($description, $category_id, $due_date, $id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM tasks;");
            if ($executed) {
                return true;
            } else {
                return false;
            }
        }

        static function find($search_id)
        {
            $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
            $returned_tasks -> bindParam(':id', $search_id, PDO::PARAM_STR);
            $returned_tasks->execute();
            foreach ($returned_tasks as $task) {
                $task_description = $task['description'];
                $category_id = $task['category_id'];
                $due_date = $task['due_date'];
                $task_id = $task['id'];
                if ($task_id == $search_id) {
                    $found_task = new Task($task_description, $category_id, $due_date, $task_id);
                }
            }
            return $found_task;
        }

        function update($new_description)
        {
            $executed = $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getId()};");
            if ($executed) {
               $this->setDescription($new_description);
               return true;
            } else {
               return false;
            }
        }

        function delete()
        {
            $executed = $GLOBALS['DB']->exec("DELETE FROM tasks WHERE category_id = {$this->getCategoryId()};");
             if ($executed) {
                return true;
            } else {
                return false;
            }
        }
    }
?>
