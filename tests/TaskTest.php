<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testSetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $test_task = new Task($description);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }

        function testGetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $test_task = new Task($description);

            //Act
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }

        function testGetId()
        {
            //Arrange
            $description = "Wash the dog";
            $test_task = new Task($description);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertTrue(is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $description = "Wash the dog";
            $test_task = new Task($description);

            //Act
            $executed = $test_task->save();

            // Assert
            $this->assertTrue($executed, "Task not successfully saved to database");
        }

        function testGetAll()
        {
            //Arrange
            $description = "Wash the dog";
            $description_2 = "Water the lawn";
            $test_task = new Task($description);
            $test_task->save();
            $test_task_2 = new Task($description_2);
            $test_task_2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $description_2 = "Water the lawn";
            $test_task = new Task($description);
            $test_task->save();
            $test_task_2 = new Task($description_2);
            $test_task_2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $description = "Wash the dog";
            $description_2 = "Water the lawn";
            $test_task = new Task($description);
            $test_task->save();
            $test_task_2 = new Task($description_2);
            $test_task_2->save();

            //Act
            $id = $test_task->getId();
            $result = Task::find($id);

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $test_task = new Task($description);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function test_deleteTask()
        {
            //Arrange
            $description = "Wash the dog";
            $test_task = new Task($description);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2);
            $test_task2->save();


            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2],Task::getAll());
        }
    }
?>
