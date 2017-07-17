<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost:8889;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', array('categories' => Category::getAll(), 'tasks' => Task::getAll()));
    });


    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array(Category::getAll()));
    });

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    // $app->post("/tasks", function() use ($app) {
    //     $description = $_POST['description'];
    //     $category_id = $_POST['category_id'];
    //     $due_date = $_POST['due-date'];
    //     $task = new Task($description, $category_id, $due_date, $id = null);
    //     $task->save();
    //     $category = Category::find($category_id);
    //     // var_dump($category->getTasks());
    //     return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'due-date' => $due_date));
    // });

    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $task = new Task($description);
        $task->save();
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });


    // $app->get("/categories/{id}", function($id) use ($app) {
    //     $category = Category::find($id);
    //     return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    // });
    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });

    $app->post("/categories", function() use ($app) {
      $category = new Category($_POST['name']);
      $category->save();
      return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
  });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });


    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/empty_category", function() use ($app) {
        $category_id = $_POST['category_id'];
        $category = Task::find($category_id);
        $category->delete();
        return $app['twig']->render('category.html.twig');
    });



    return $app;
?>
