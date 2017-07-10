<?php
class Task
{
    private $description;
    private $id;

    function __construct($description, $id = null)
    {
        $this->description = $description;
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }


    function save()
    {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
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
            $id = $task['id'];
            $new_task = new Task($description, $id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function find($search_id)
    {
        $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
        $returned_tasks -> bindParam(':id', $search_id, PDO::PARAM_STR);
        $returned_tasks->execute();
        foreach ($returned_tasks as $task) {
            $task_description = $task['description'];
            $task_id = $task['id'];
            if ($task_id == $search_id) {
                $found_task = new Task($task_description, $task_id);
            }
        }
        return $found_task;
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
}
?>
